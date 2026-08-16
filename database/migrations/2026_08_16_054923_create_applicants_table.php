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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
                $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('password');
            $table->string('father_name');
            $table->text('photo');
            $table->string('cnic');
            $table->text("address");
            $table->enum('gender',["male","female"])->default("female");
            $table->string('month_of_exprience');
            $table->decimal("current_salary");
            $table->string("current_company");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};