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
        Schema::create('wallet_topup_requests', function (Blueprint $table) {
            $table->id();

    $table->foreignId('customer_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('wallet_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->decimal('amount', 12, 2);

    $table->enum('payment_method', [
        'cash',
        'bank_transfer',
        'easypaisa',
        'jazzcash',
        'card'
    ]);

    $table->string('transaction_id')->nullable();

    // Card Details
    $table->string('card_holder_name')->nullable();
    $table->string('card_number')->nullable();
    $table->string('card_expiry')->nullable();

    // Easypaisa / JazzCash
    $table->string('mobile_account_name')->nullable();
    $table->string('mobile_account_number')->nullable();

    // Bank Transfer
    $table->string('bank_name')->nullable();
    $table->string('account_title')->nullable();
    $table->string('account_number')->nullable();
    $table->string('iban')->nullable();

    $table->text('notes')->nullable();

    $table->enum('status', [
        'pending',
        'approved',
        'rejected'
    ])->default('pending');

    $table->foreignId('approved_by')
        ->nullable()
        ->constrained('employees')
        ->nullOnDelete();

    $table->timestamp('approved_at')->nullable();

    $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_topup_requests');
    }
};