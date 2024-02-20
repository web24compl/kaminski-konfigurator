<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionsAndAnswersSeeder extends Seeder
{
    public function run(): void
    {
        $q1 = Question::factory()->create([
            'question' => 'W jakim kraju mieszkasz?'
        ]);

        $q2 = Question::factory()->create([
            'question' => 'Mówisz po polsku?'
        ]);

        $q3 = Question::factory()->create([
            'question' => 'Mówisz po holendersku?'
        ]);


        $q1_a1 = Answer::factory()->create([
            'answer' => 'Polska',
            'question_id' => $q2
        ]);

        $q1_a2 = Answer::factory()->create([
            'answer' => 'Holandia',
            'question_id' => $q3
        ]);

        $q1->answers()->saveMany(collect([$q1_a1, $q1_a2]));


        $q2_a1 = Answer::factory()->create([
            'answer' => 'Tak',
            'question_id' => null
        ]);

        $q2_a2 = Answer::factory()->create([
            'answer' => 'Nie',
            'question_id' => null
        ]);

        $q2->answers()->saveMany(collect([$q2_a1, $q2_a2]));


        $q3_a1 = Answer::factory()->create([
            'answer' => 'Tak',
            'question_id' => null
        ]);

        $q3_a2 = Answer::factory()->create([
            'answer' => 'Nie',
            'question_id' => null
        ]);

        $q3->answers()->saveMany(collect([$q3_a1, $q3_a2]));
    }
}
