<?php

namespace App\Mail;

use App\Exports\ExportFilteredResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class InterruptedFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected string $email, protected string $phone, protected array $input)
    {
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->markdown('mails.interrupted-form-mail', [
                'email' => $this->email,
                'phone' => $this->phone,
                'input' => $this->input,
            ])
            ->subject('Informacje o przerwanym wypełnianiu formularza');
    }
}
