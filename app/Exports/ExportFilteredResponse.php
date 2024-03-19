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
                    'responseItemId' => $this->response[$index]['id'],
                    'responseItemName' => $this->response[$index]['name']
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
        foreach ($this->response as $key => $item) {
            if(!isset($data[$key])) {
                $data[$key] = ['mail' => '','assistant' => '', 'user' => ''];
            }
            $data[$key]['responseItemId'] = $item['id'];
            $data[$key]['responseItemName'] = $item['name'];
        }

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
