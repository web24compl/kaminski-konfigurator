<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportFilteredResponse implements FromArray, WithHeadings
{
    protected $data;

    protected $email;

    protected $response;

    protected $emailPrinted = false;

    public function __construct($input, $email, $response)
    {
        $this->data = $input;
        $this->email = $email;
        $this->response = $response;
    }

    public function array(): array
    {
        $data = [];
        $index = 0;

        foreach ($this->data as $row) {
            if (!$this->emailPrinted) {
                $data[] = [
                    'mail' => $this->email,
                    'assistant' => '',
                    'user' => '',
                    'responseItemId' => $this->response['id'],
                    'responseItemName' => $this->response['name']
                ];
                $this->emailPrinted = true;
            }

            if (!isset($data[$index])) {
                $data[$index] = ['mail' => '','assistant' => '', 'user' => ''];
            }

            if ($row['role'] === 'assistant') {
                $data[$index]['assistant'] .= $row['content'];
            }

            if ($row['role'] === 'user') {
                $data[$index]['user'] .= $row['content'];
                $index++;
            }
        }
//        dd($data);
        return $data;
    }

    public function headings(): array
    {
        return [
            'Mail',
            'Pytanie',
            'Odpowiedz',
            'Nr Produktu',
            'Nazwa Produktu'
        ];
    }
}
