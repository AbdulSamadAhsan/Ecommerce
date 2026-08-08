<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('purchase_order_id');
            $table->bigInteger('product_id');
            $table->decimal(12, 2)('quantity');
            $table->decimal(12, 2)('received_quantity')->default(0.00);
            $table->decimal(15, 2)('unit_price');
            $table->decimal(15, 2)('discount')->default(0.00);
            $table->decimal(15, 2)('tax')->default(0.00);
            $table->decimal(15, 2)('total');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
