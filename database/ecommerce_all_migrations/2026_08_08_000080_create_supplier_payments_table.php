<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('supplier_id');
            $table->bigInteger('purchase_id')->nullable()->default(null);
            $table->decimal(12, 2)('amount');
            $table->enum('payment_method', ['CASH', 'BANK_TRANSFER', 'CHEQUE', 'CARD', 'WALLET']);
            $table->string('transaction_id')->nullable()->default(null);
            $table->date('payment_date');
            $table->text('notes')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};
