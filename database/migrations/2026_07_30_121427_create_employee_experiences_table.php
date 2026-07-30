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
        Schema::create('employee_experiences', function (Blueprint $table) {
            $table->id();
             $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();
           $table->decimal("month_of_experience");
           $table->string("designation");
            $table->string("company");
            $table->date("start_date");
                $table->date("end_date");
            $table->enum("experience_type",["cooperate","freelance"])->default("cooperate");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_experiences');
    }
};