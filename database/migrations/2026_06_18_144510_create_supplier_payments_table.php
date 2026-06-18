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
        
        Schema::create('supplier_payments', function (Blueprint $table) {
    $table->id();

    $table->foreignId('supplier_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('purchase_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->decimal('amount', 12, 2);

    $table->enum('payment_method', [
        'cash',
        'bank_transfer',
        'cheque',
        'card',
        'wallet'
    ]);

    $table->string('transaction_id')->nullable();

    $table->date('payment_date');

    $table->text('notes')->nullable();

    $table->timestamps();
});
         
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};