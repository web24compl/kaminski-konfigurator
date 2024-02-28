<?php

declare(strict_types=1);

namespace Configurator\QAndATree\Rules;

use App\Models\QAndATreeItem;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ParentItemHesQuestion implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $contains = QAndATreeItem::query()
            ->whereNotNull('question_text')
            ->find($this->data['parent_question_id']);


        if (!$contains) {
            $fail('Element nadrzędny musi zawierać pytanie aby móc zdefiniować odpowiedź');
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
