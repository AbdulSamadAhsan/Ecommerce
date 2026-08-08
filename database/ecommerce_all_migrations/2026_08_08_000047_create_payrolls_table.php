<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('employee_id');
            $table->string('month');
            $table->decimal(12, 2)('basic_salary');
            $table->decimal(12, 2)('allowances')->default(0.00);
            $table->decimal(12, 2)('bonus')->default(0.00);
            $table->decimal(12, 2)('overtime')->default(0.00);
            $table->decimal(12, 2)('deductions')->default(0.00);
            $table->decimal(12, 2)('tax')->default(0.00);
            $table->decimal(12, 2)('net_salary');
            $table->enum('status', ['PENDING', 'PAID'])->default('pending');
            $table->date('paid_date')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
