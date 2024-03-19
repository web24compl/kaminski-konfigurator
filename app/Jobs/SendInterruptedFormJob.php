<?php

namespace App\Jobs;

use App\Mail\InterruptedFormMail;
use App\Models\ChatResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInterruptedFormJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $email;
    protected array $messages;
    protected string $phone;
    protected string $uuid;
    protected string $uniqueJobId;

    public function __construct($email, $messages, $phone, $uuid, $uniqueJobId)
    {
        $this->email = $email;
        $this->messages = $messages;
        $this->phone = $phone;
        $this->uuid = $uuid;
        $this->uniqueJobId = $uniqueJobId;
    }

    public function handle()
    {
        if(ChatResponse::where('uuid', $this->uuid)->exists()) {
            $chatResponse = ChatResponse::where('uuid', $this->uuid)->first();
            if($chatResponse->job_id == $this->uniqueJobId) {
                Mail::to(nova_get_setting('sales_email'))
                    ->send(new InterruptedFormMail($this->email, $this->phone, $this->messages));
            }
            else {
                $this->delete();
            }
        }
    }
}
