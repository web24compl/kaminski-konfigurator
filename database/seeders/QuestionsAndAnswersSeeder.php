<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\QAndATreeItem;
use Illuminate\Database\Seeder;

class QuestionsAndAnswersSeeder extends Seeder
{
    public function run(): void
    {

        $q1 = QAndATreeItem::factory()->create([
            'question_text' => 'W jakim kraju mieszkasz?',
            'answer_text' => '',
        ]);

        $q1_1 = QAndATreeItem::factory()->create([
            'parent_question_id' => $q1->id,
            'answer_text' => 'W Polsce',
            'question_text' => 'Mówisz po polsku?'
        ]);

        $q1_1_1 = QAndATreeItem::factory()->create([
            'parent_question_id' => $q1_1->id,
            'answer_text' => 'Tak',
        ]);
        $q1_1_2 = QAndATreeItem::factory()->create([
            'parent_question_id' => $q1_1->id,
            'answer_text' => 'Nie',
        ]);

        $q1_2 = QAndATreeItem::factory()->create([
            'parent_question_id' => $q1->id,
            'answer_text' => 'W Holandii',
            'question_text' => 'Mówisz po holendersku?'
        ]);

        $q1_2_1 = QAndATreeItem::factory()->create([
            'parent_question_id' => $q1_2->id,
            'answer_text' => 'Tak',
        ]);
        $q1_2_2 = QAndATreeItem::factory()->create([
            'parent_question_id' => $q1_2->id,
            'answer_text' => 'Nie',
            'question_text' => 'Zamierzasz się uczyć?'
        ]);

        $q1_2_2_1 = QAndATreeItem::factory()->create([
            'parent_question_id' => $q1_2_2->id,
            'answer_text' => 'Tak',
        ]);
        $q1_2_2_2 = QAndATreeItem::factory()->create([
            'parent_question_id' => $q1_2_2->id,
            'answer_text' => 'Nie',
        ]);

    }
}
