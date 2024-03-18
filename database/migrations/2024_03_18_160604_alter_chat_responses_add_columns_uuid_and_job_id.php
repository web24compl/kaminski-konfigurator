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
        Schema::table('chat_responses', function (Blueprint $table) {
            $table->string('uuid')->nullable();
            $table->unsignedInteger('job_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_responses', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->dropColumn('job_id');
        });
    }
};
