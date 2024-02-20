<?php

namespace Database\Factories;

use App\Models\ChatResponse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChatResponseFactory extends Factory
{
    protected $model = ChatResponse::class;

    public function definition(): array
    {
        return [
            'input' => $this->faker->words(),
            'response' => $this->faker->words(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'tokens' => $this->faker->randomNumber(),
            'mail' => $this->faker->word(),
        ];
    }
}
