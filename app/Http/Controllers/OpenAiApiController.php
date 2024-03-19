<?php

namespace App\Http\Controllers;

use App\Jobs\SendInterruptedFormJob;
use App\Mail\SalesInformationMail;
use App\Models\ChatResponse;
use App\Models\QAndATreeItem;
use App\Models\SystemMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\Response;

class OpenAiApiController extends Controller
{
    CONST PRODUCTS_CACHE_KEY = 'woocommerce_products';
    CONST PRODUCTS_DIRTY_CACHE_KEY = 'woocommerce_products_dirty';
    CONST QUESTIONS_CACHE_KEY = 'questions';
    CONST CACHE_DURATION = 60 * 60 * 24;
    CONST CACHE_DURATION_TREE = 60 * 60 * 24 * 7;

    public function getWoocomerceProducts(): array
    {
        $cacheDuration = time() + self::CACHE_DURATION;

        if (Cache::has(self::PRODUCTS_CACHE_KEY)) {
            $filteredProducts = Cache::get(self::PRODUCTS_CACHE_KEY);
        }
        else {
            $url = config('woocommerce.api_url') . '/products?per_page=100&status=publish';
            $consumerKey = config('woocommerce.consumer_key');
            $consumerSecret = config('woocommerce.consumer_secret');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            $response = curl_exec($ch);
            curl_close($ch);

            $products = json_decode($response);
            $filteredProducts = [];

            foreach ($products as $product) {
                $descriptionConfig = array_reduce($product->meta_data, function($carry, $item) {
                    return $item->key === '_custom_textarea' ? $item->value : $carry;
                }, null);

                $shortDescription = array_reduce($product->meta_data, function($carry, $item) {
                    return $item->key === '_custom_short_description' ? $item->value : $carry;
                }, null);

                $filteredProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'tags' => $product->tags,
                    'categories' => $product->categories,
                    'description' => str_replace("\n", '', strip_tags($product->description)),
                    'descriptionhtml' => $product->description,
                    'shortDescription' => nl2br($shortDescription),
                    'image' => $product->images[0]->src,
                    'permalink' => $product->permalink,
                    'descriptionConfig' => $descriptionConfig,
                ];
            }
            Cache::put(self::PRODUCTS_CACHE_KEY, $filteredProducts, $cacheDuration);
        }
        return $filteredProducts;
    }

    public function show()
    {
        if(Cache::has(self::QUESTIONS_CACHE_KEY)){
            $questions = Cache::get(self::QUESTIONS_CACHE_KEY);
        }
        else {
            $questions = QAndATreeItem::with(['answers', 'parentQuestion'])->get();
            Cache::put(self::QUESTIONS_CACHE_KEY, $questions, self::CACHE_DURATION_TREE);
        }
        return view('layouts.app', ['questions' => $questions]);
    }

    public function validateCaptcha (Request $request): \Illuminate\Http\JsonResponse
    {
        $token = $request->input('token');
        if (empty($token)) {
            return response()->json(['message' => 'Captcha token is required'], 400);
        }
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => config('recaptcha.api_secret_key'),
            'response' => $token
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $resultJson = json_decode($response);

        if ($resultJson->score > 0.5) {
            return response()->json($resultJson, Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'Captcha validation failed'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function index(Request $request)
    {
        if($request->get('questions') == null || $request->get('answers') == null ||
           $request->get('questions') == [] || $request->get('answers') == [])
        {
            return response()->json(['error' => 'Questions or answers are empty'], Response::HTTP_BAD_REQUEST);
        }

        $email = $request->get('email');
        $phone = $request->get('phone') ?? 'Brak';
        $questions = $request->get('questions');
        $answers = $request->get('answers');
        $uuid = $request->get('uuid');

        $systemMessages = SystemMessage::all()->pluck('content')->toArray();
        array_push($systemMessages, "Produkty z których wybierasz na podstawie odpowiedzi klienta");

        $wordpressElements = $this->getWoocomerceProducts();

        foreach ($wordpressElements as $element) {

            $product = "id: " . $element['id'] .
                ", name: " . $element['name'] .
                ", price: " . $element['price'];

            $product .= $element['descriptionConfig'] != '' ? ", description: " . $element['descriptionConfig'] : '';

            array_push($systemMessages, $product);
        }

        array_push($systemMessages,'zwróć json tablice products z 5 obiektami o kluczach id i name');

        $systemMessages = $this->setMessageFromArray('system', $systemMessages);

        $questions = $this->setMessageFromArray('assistant', $questions);
        $answers = $this->setMessageFromArray('user', $answers);
        for ($i = 0; $i < count($questions); $i++){
            array_push($systemMessages, $questions[$i]);
            array_push($systemMessages, $answers[$i]);
        }

        $gptResponse = OpenAI::chat()->create([
            'messages' => $systemMessages,
            'temperature' => 0,
            'model' => config('openai.default_engine'),
            'response_format' => ['type' => 'json_object'],
        ]);

        $chatResponse = ChatResponse::where('uuid', $uuid)->exists()
            ? ChatResponse::where('uuid', $uuid)->first()
            : new ChatResponse();

        $products = json_decode($gptResponse->choices[0]->message->content)->products;
        foreach ($products as $key => $product) {
            $products[$key] = $this->getProductFromId($product->id, $wordpressElements);
        }

        $chatResponse->input = $systemMessages;
        $chatResponse->response = json_decode($gptResponse->choices[0]->message->content)->products;
        $chatResponse->tokens = $gptResponse->usage->totalTokens;
        $chatResponse->mail = $email;
        $chatResponse->phone = $phone;
        $chatResponse->uuid = $uuid;
        $chatResponse->job_id = '';
        $chatResponse->save();

        Mail::to(nova_get_setting('sales_email'))
            ->send(new SalesInformationMail($email, $chatResponse->input, $chatResponse->response, $phone));

        return response()->json($products, Response::HTTP_OK);
    }

    public function interruptedTest(Request $request)
    {
        $email = $request->get('email');
        $phone = $request->get('phone') ?? 'Brak';
        $questions = $request->get('questions');
        $answers = $request->get('answers');
        $uuid = $request->get('uuid');
        $jobId = Str::random(10);

        $messages = [];
        $questions = $this->setMessageFromArray('assistant', $questions);
        $answers = $this->setMessageFromArray('user', $answers);
        for ($i = 0; $i < count($questions); $i++){
            array_push($messages, $questions[$i]);
            array_push($messages, $answers[$i]);
        }

//        $delayTime = Carbon::tomorrow()->setTime(8, 0);
        $delayTime = Carbon::now()->addSeconds(30);
        $job = (new SendInterruptedFormJob($email, $messages, $phone, $uuid, $jobId));
        Queue::later($delayTime,$job);

        if(ChatResponse::where('uuid', $uuid)->exists()){
            $chatResponse = ChatResponse::where('uuid', $uuid)->first();
            $chatResponse->input = $messages;
            $chatResponse->job_id = $jobId;
            $chatResponse->save();
        }
        else {
            $chatResponse = new ChatResponse();
            $chatResponse->input = $messages;
            $chatResponse->response = [];
            $chatResponse->tokens = 0;
            $chatResponse->mail = $email;
            $chatResponse->phone = $phone;
            $chatResponse->uuid = $uuid;
            $chatResponse->job_id = $jobId;
            $chatResponse->save();
        }


    }

    private function setMessageFromArray(string $role, array $messages): array
    {
        return array_map(function ($message) use ($role) {
            return ['role' => $role, 'content' => $message];
        }, $messages);
    }

    private function getProductFromId($id, $products)
    {
        foreach ($products as $product) {
            if ($product['id'] == $id) {
                return $product;
            }
        }
        return null;
    }
}
