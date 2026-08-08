<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('reference_no');
            $table->enum('type', ['SALE', 'PURCHASE', 'PAYMENT', 'REFUND', 'CASHBACK']);
            $table->decimal(12, 2)('amount');
            $table->text('description')->nullable()->default(null);
            $table->timestamp('transaction_date')->default(current_timestamp());
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
