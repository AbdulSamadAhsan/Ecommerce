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
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('salary_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->string('month'); // example: 2026-06

    $table->decimal('amount', 12, 2);

    $table->enum('payment_method', [
        'cash',
        'bank_transfer',
        'cheque',
        'easypaisa',
        'jazzcash'
    ])->default('cash');

    $table->string('transaction_id')->nullable();

    $table->date('paid_date');

    $table->enum('status', [
        'pending',
        'paid',
        'cancelled'
    ])->default('paid');

    $table->text('notes')->nullable();

    $table->timestamps();

        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};