<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('sale_id');
            $table->bigInteger('product_id');
            $table->integer('quantity');
            $table->decimal(12, 2)('unit_price');
            $table->decimal(12, 2)('total_price');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
