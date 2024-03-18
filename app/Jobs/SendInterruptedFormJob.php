<?php

namespace App\Jobs;

use App\Mail\InterruptedFormMail;
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

    public function __construct($email, $messages, $phone)
    {
        $this->email = $email;
        $this->messages = $messages;
        $this->phone = $phone;
    }

    public function handle()
    {
        Mail::to(nova_get_setting('sales_email'))
            ->send(new InterruptedFormMail($this->email, $this->phone, $this->messages));
    }
}
