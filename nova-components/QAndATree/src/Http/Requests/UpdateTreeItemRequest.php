<?php

declare(strict_types=1);

namespace Configurator\QAndATree\Http\Requests;

use App\Models\QAndATreeItem;
use Configurator\QAndATree\Rules\ItemHesRelatedAnswers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTreeItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parent_question_id' => [
                Rule::requiredIf($this->treeHasFirstItem()),
                'integer',
                'exists:App\Models\QAndATreeItem,id'
            ],
            'question_text' => [
                new ItemHesRelatedAnswers(),
            ],
            'answer_text' => [
                Rule::requiredIf(fn() => request('parent_question_id')),
            ],
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

    private function treeHasFirstItem(): bool
    {
        return QAndATreeItem::query()
            ->whereNull('parent_question_id')
            ->whereNot('parent_question_id', request('id'))
            ->exists();
    }
}
