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
        Schema::create('product_barcodes', function (Blueprint $table) {
            $table->id();
                $table->foreignId('product_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('barcode')->unique();

    $table->enum('barcode_type', [
        'CODE128',
        'EAN13',
        'EAN8',
        'UPC'
    ])->default('CODE128');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_barcodes');
    }
};