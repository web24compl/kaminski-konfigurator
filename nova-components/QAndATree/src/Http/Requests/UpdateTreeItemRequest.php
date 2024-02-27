<?php

declare(strict_types=1);

namespace Configurator\QAndATree\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTreeItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parent_question_id' => [
                Rule::requiredIf(fn() => !request('id')),
                'integer',
                'exists:App\Models\Housekeeper,id'
            ],
            'question_text' => [Rule::requiredIf(fn() => !request('id')), 'integer', 'exists:App\Models\Client,id'],
            'answer_text' => [Rule::requiredIf(fn() => !request('id')), 'integer', 'exists:App\Models\Service,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'parent_question_id.required' => 'To pole jest wymagane',
            'question_text.required' => 'To pole jest wymagane',
            'answer_text.required' => 'To pole jest wymagane',
        ];
    }
}
