<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_work_experiences', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->decimal(8, 2)('month_of_experience');
            $table->string('designation');
            $table->string('company');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('experience_type', ['COOPERATE', 'FREELANCE'])->default('cooperate');
            $table->text('responsibility');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_work_experiences');
    }
};
