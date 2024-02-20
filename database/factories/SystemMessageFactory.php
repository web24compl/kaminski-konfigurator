<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SystemMessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SystemMessageFactory extends Factory
{
    protected $model = SystemMessage::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
