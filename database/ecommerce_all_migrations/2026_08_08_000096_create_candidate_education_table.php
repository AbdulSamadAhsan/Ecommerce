<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_education', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('job_application_id');
            $table->string('graduate_year');
            $table->decimal(8, 2)('grade');
            $table->string('degree_name')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_education');
    }
};
