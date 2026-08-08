<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_topup_requests', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('customer_id');
            $table->bigInteger('wallet_id')->nullable()->default(null);
            $table->decimal(12, 2)('amount');
            $table->enum('payment_method', ['CASH', 'BANK_TRANSFER', 'EASYPAISA', 'JAZZCASH', 'CARD']);
            $table->string('transaction_id')->nullable()->default(null);
            $table->string('card_holder_name')->nullable()->default(null);
            $table->string('card_number')->nullable()->default(null);
            $table->string('card_expiry')->nullable()->default(null);
            $table->string('mobile_account_name')->nullable()->default(null);
            $table->string('mobile_account_number')->nullable()->default(null);
            $table->string('bank_name')->nullable()->default(null);
            $table->string('account_title')->nullable()->default(null);
            $table->string('account_number')->nullable()->default(null);
            $table->string('iban')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('pending');
            $table->bigInteger('approved_by')->nullable()->default(null);
            $table->timestamp('approved_at')->nullable()->default(null);
            $table->text('rejection_reason')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_topup_requests');
    }
};
