<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('employee_id');
            $table->decimal(12, 2)('basic_salary');
            $table->decimal(12, 2)('allowance')->default(0.00);
            $table->date('effective_from');
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('tax_deduction');
            $table->string('net_salary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
