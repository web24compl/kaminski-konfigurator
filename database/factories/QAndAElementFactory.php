<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\QAndAElement;
use Illuminate\Database\Eloquent\Factories\Factory;

class QAndAElementFactory extends Factory
{
    protected $model = QAndAElement::class;

    public function definition(): array
    {
        return [
            'question_text' => '',
            'answer_text' => '',
        ];
    }
}
