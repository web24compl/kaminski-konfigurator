<?php

namespace App\Http\Controllers;

use App\Mail\SalesInformationMail;
use App\Models\ChatResponse;
use App\Models\Question;
use App\Models\SystemMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAiApiController extends Controller
{

    public function show()
    {
        $questions = Question::with('answers')->get();

        return view('layouts.app', ['questions' => $questions]);
    }

    public function validateCaptcha (Request $request)
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
            return response()->json($resultJson, 200);
        } else {
            return response()->json(['message' => 'Captcha validation failed'], 400);
        }
    }

    public function index(Request $request)
    {

        $email = $request->get('email');

//        $wordpressElements = Opisy produktów od nich z wordpressa
        if($request->get('questions') == null || $request->get('answers') == null ||
           $request->get('questions') == [] || $request->get('answers') == [])
        {
            return response()->json(['error' => 'questions or answers are empty'], 400);
        }

        $questions = $request->get('questions');
        $answers = $request->get('answers');
        $systemMessages = SystemMessage::all()->pluck('content')->toArray();

        $systemMessages = $this->setMessageFromArray('system', $systemMessages);

        array_push($systemMessages, ['role' => 'system', 'content' => 'zwróć json id, name, similar']);

        $questions = $this->setMessageFromArray('assistant', $questions);
        $answers = $this->setMessageFromArray('user', $answers);
        for ($i = 0; $i < count($questions); $i++){
            array_push($systemMessages, $questions[$i]);
            array_push($systemMessages, $answers[$i]);
        }

        $response = OpenAI::chat()->create([
            'messages' => $systemMessages,
            'temperature' => 0,
            'model' => config('openai.default_engine'),
            'max_tokens' => 40,
            'response_format' => ['type' => 'json_object'],
        ]);

        $chatResponse = new ChatResponse();
        $chatResponse->input = $systemMessages;
        $chatResponse->response = json_decode($response->choices[0]->message->content);
        $chatResponse->tokens = $response->usage->totalTokens;
        $chatResponse->mail = $email;
        $chatResponse->save();

        Mail::to(nova_get_setting('sales_email'))
            ->send(new SalesInformationMail($email, $chatResponse->input, $chatResponse->response));
        return response()->json($chatResponse->response, 200);
    }

    private function setMessageFromArray(string $role, array $messages): array
    {
        return array_map(function ($message) use ($role) {
            return ['role' => $role, 'content' => $message];
        }, $messages);
    }
}
