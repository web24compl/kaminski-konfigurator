<?php

declare(strict_types=1);

namespace Configurator\QAndATree\Rules;

use App\Models\QAndATreeItem;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ItemHesRelatedAnswers implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $contains = QAndATreeItem::query()
            ->whereParentQuestionId($this->data['id'])
            ->exists();

        if ($contains && empty($value)) {
            $fail('Element zawiera przypisane odpowiedzi i musi zawierać treść pytania.');
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
