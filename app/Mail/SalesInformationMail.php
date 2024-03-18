<?php

namespace App\Mail;

use App\Exports\ExportFilteredResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class SalesInformationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected string $email, protected $input, protected $gptResponse, protected string $phone)
    {
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
                'phone' => $this->phone,
            ])
            ->subject('Informacje o sprzedaży')
            ->attach(Excel::download(new ExportFilteredResponse($this->input, $this->email, $this->gptResponse), 'input.csv')
                ->getFile(), ['as' => 'list.csv'])
            ->from($this->email);
    }
}
