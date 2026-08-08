<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('warehouse_id')->nullable()->default(null);
            $table->bigInteger('supplier_id');
            $table->bigInteger('category_id');
            $table->string('name');
            $table->string('sku');
            $table->decimal(12, 2)('purchase_price');
            $table->decimal(12, 2)('selling_price');
            $table->integer('quantity')->default(0);
            $table->integer('minimum_stock')->default(5);
            $table->text('description')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->string('status')->default('1');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->bigInteger('brand_id');
            $table->decimal(12, 2)('discount')->default(0.00);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
