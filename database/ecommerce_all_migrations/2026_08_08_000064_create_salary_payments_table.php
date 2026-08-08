<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('employee_id');
            $table->bigInteger('salary_id')->nullable()->default(null);
            $table->bigInteger('payroll_id');
            $table->decimal(12, 2)('amount');
            $table->enum('payment_method', ['CASH', 'BANK', 'CHEQUE', 'EASYPAISA', 'JAZZCASH'])->default('cash');
            $table->string('transaction_id')->nullable()->default(null);
            $table->date('paid_date');
            $table->enum('status', ['PENDING', 'PAID', 'CANCELLED'])->default('paid');
            $table->text('notes')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};
