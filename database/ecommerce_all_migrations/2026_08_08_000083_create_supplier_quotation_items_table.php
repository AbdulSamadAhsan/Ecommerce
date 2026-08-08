<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_quotation_items', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('supplier_quotation_id');
            $table->bigInteger('product_id');
            $table->decimal(12, 2)('quantity');
            $table->decimal(15, 2)('unit_price');
            $table->decimal(15, 2)('discount')->default(0.00);
            $table->decimal(15, 2)('tax')->default(0.00);
            $table->decimal(15, 2)('total');
            $table->text('remarks')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_quotation_items');
    }
};
