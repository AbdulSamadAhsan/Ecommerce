<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('purchase_id');
            $table->bigInteger('product_id');
            $table->integer('quantity');
            $table->decimal(12, 2)('purchase_price');
            $table->decimal(12, 2)('subtotal');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
