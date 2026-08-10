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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

    $table->foreignId('job_posting_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('full_name');

    

    $table->string('email');

    $table->string('phone');

    $table->string('password');

    $table->string('last_education');
    $table->string('month_of_exprience');

    $table->decimal('expected_salary',10,2)->nullable();

    $table->date('available_from')->nullable();

    $table->enum('status',[
        'pending',
        'shortlisted',
        'interview',
        'rejected',
        'hired'
    ])->default('pending');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};