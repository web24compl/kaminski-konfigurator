<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SalesInformationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected string $email, protected $gptResponse)
    {
        //
        $this->name = $this->gptResponse['name'];
        $this->id = $this->gptResponse['id'];
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->markdown('mails.sales-information-mail', [
                'email' => $this->email,
                'name' => $this->name,
                'id' => $this->id,
            ])
            ->subject('Informacje o sprzedaży');

    }
}
