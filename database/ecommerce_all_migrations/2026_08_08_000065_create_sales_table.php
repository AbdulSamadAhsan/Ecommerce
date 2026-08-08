<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('customer_id')->nullable()->default(null);
            $table->string('invoice_no');
            $table->decimal(12, 2)('subtotal');
            $table->decimal(12, 2)('discount')->default(0.00);
            $table->decimal(12, 2)('tax')->default(0.00);
            $table->decimal(12, 2)('total_amount');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('shipping_cost')->default('120');
            $table->enum('payment_method', ['CASH', 'CARD', 'BANK', 'JAZZCASH', 'EASYPAISA'])->default('cash');
            $table->enum('payment_status', ['PENDING', 'PARTIAL', 'PAID', 'FAILED', 'REFUNDED'])->default('pending');
            $table->decimal(12, 2)('paid_amount')->default(0.00);
            $table->decimal(12, 2)('due_amount')->default(0.00);
            $table->enum('sale_type', ['POS', 'ONLINE'])->default('pos');
            $table->bigInteger('cashier_id')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
