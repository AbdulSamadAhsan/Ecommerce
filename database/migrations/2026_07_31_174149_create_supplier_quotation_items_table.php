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
        Schema::create('supplier_quotation_items', function (Blueprint $table) {
            $table->id();
                $table->foreignId('supplier_quotation_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('product_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->decimal('quantity', 12, 2);

    $table->decimal('unit_price', 15, 2);

    $table->decimal('discount', 15, 2)->default(0);

    $table->decimal('tax', 15, 2)->default(0);

    $table->decimal('total', 15, 2);

    $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_quotation_items');
    }
};