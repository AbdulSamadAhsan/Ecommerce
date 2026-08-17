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
        Schema::create('interview_feedback', function (Blueprint $table) {
            $table->id();
              $table->foreignId('interview_id')
        ->constrained()
        ->cascadeOnDelete();


    $table->unsignedTinyInteger('communication_score');

    $table->unsignedTinyInteger('attitude_score');

    $table->unsignedTinyInteger('overall_score');

    $table->text('comments');

    $table->boolean('recommended');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_feedback');
    }
};