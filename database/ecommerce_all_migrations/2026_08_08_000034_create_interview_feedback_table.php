<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_feedback', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('interview_id');
            $table->tinyInteger('technical_score');
            $table->tinyInteger('communication_score');
            $table->tinyInteger('attitude_score');
            $table->tinyInteger('overall_score');
            $table->text('comments');
            $table->boolean('recommended');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_feedback');
    }
};
