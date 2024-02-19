<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answer_question', function (Blueprint $table) {
            $table->unsignedBigInteger('answer_id');
            $table->unsignedBigInteger('question_id');

            $table->foreign('answer_id')->references('id')->on('answers')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');

            // Define a unique constraint to prevent duplicates
            $table->unique(['answer_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_question');
    }
};
