<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('department_id');
            $table->bigInteger('created_by');
            $table->string('job_title');
            $table->text('description');
            $table->text('responsibilities')->nullable()->default(null);
            $table->text('requirements')->nullable()->default(null);
            $table->text('benefits')->nullable()->default(null);
            $table->integer('vacancies')->default(1);
            $table->decimal(10, 2)('minimum_salary')->nullable()->default(null);
            $table->decimal(10, 2)('maximum_salary')->nullable()->default(null);
            $table->enum('employment_type', ['PERMANENT', 'PART-TIME', 'CONTRACT', 'INTERNSHIP']);
            $table->enum('work_mode', ['ONSITE', 'REMOTE', 'HYBRID']);
            $table->date('closing_date');
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
