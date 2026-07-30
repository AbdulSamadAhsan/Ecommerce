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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
$table->foreignId('department_id')->constrained();

  

    $table->foreignId('created_by')
        ->constrained('users');

    $table->string('job_title');

    $table->text('description');

    $table->text('responsibilities')->nullable();

    $table->text('requirements')->nullable();

    $table->text('benefits')->nullable();

    $table->unsignedInteger('vacancies')->default(1);

    $table->decimal('minimum_salary',10,2)->nullable();

    $table->decimal('maximum_salary',10,2)->nullable();

    $table->enum('employment_type',[
        'permanent',
        'part-time',
        'contract',
        'internship'
    ]);

    $table->enum('work_mode',[
        'onsite',
        'remote',
        'hybrid'
    ]);

    $table->date('closing_date');

    $table->boolean('is_active')->default(true);



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};