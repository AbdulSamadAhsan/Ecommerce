<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
              $table->foreignId('sale_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('product_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->integer('quantity');
    $table->decimal('unit_price', 12, 2);
    $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    if (Schema::hasColumn('sale_items', 'product_id')) {
        $table->dropForeign('product_id');
    }

    if (Schema::hasColumn('sale_items', 'sale_id')) {
        $table->dropForeign('sale_id');
    }


        Schema::dropIfExists('sale_items');
    }
};