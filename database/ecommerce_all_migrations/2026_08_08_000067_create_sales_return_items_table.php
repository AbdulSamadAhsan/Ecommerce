<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_return_items', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('sales_return_id');
            $table->bigInteger('product_id');
            $table->integer('quantity');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('unit_price')->default('0');
            $table->string('total_price')->default('0');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_return_items');
    }
};
