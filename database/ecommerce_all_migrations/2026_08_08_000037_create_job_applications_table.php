<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('job_posting_id');
            $table->string('full_name');
            $table->string('father_name');
            $table->date('date_of_birth');
            $table->string('photo');
            $table->string('email');
            $table->string('phone');
            $table->string('resume');
            $table->string('last_education');
            $table->string('last_institute');
            $table->string('month_of_exprience');
            $table->string('cnic');
            $table->string('address');
            $table->decimal(10, 2)('expected_salary')->nullable()->default(null);
            $table->date('available_from')->nullable()->default(null);
            $table->enum('status', ['PENDING', 'SHORTLISTED', 'INTERVIEW', 'REJECTED', 'HIRED'])->default('pending');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->enum('gender', ['FEMALE', 'MALE']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
