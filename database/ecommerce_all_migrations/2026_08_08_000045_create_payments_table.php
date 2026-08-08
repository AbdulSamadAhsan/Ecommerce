<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('order_id')->nullable()->default(null);
            $table->decimal(12, 2)('amount');
            $table->string('transaction_id')->nullable()->default(null);
            $table->enum('status', ['PENDING', 'PAID', 'FAILED'])->default('pending');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->bigInteger('purchase_id')->nullable()->default(null);
            $table->bigInteger('payment_method_id')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
