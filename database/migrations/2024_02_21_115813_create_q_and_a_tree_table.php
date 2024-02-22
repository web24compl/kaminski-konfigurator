<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('q_and_a_tree', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->text('answer_text');

            $table->foreignId('parent_question_id')->nullable()->constrained('q_and_a_tree');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('q_and_a_tree');
    }
};
