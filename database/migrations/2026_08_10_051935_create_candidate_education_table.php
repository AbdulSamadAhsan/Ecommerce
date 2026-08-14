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
        Schema::create('candidate_educations', function (Blueprint $table) {
            $table->id();
             $table->foreignId('job_application_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->date('graduate_start_year');
                 $table->date('graduate_end_year');
            $table->decimal("grade");
            $table->string('degree_name')->nullable()->default(null);
                   $table->string('institute_type')->nullable()->default(null);
             $table->string('institute')->nullable()->default(null);
             $table->text('certificate_path')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_educations');
    }


};