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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
                $table->foreignId('supplier_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('purchase_no')->unique();

    $table->decimal('subtotal', 12, 2);
    $table->decimal('discount', 12, 2)->default(0);
    $table->decimal('tax', 12, 2)->default(0);
    $table->decimal('total', 12, 2);

    $table->date('purchase_date');

    $table->enum('status', [
        'pending',
        'received',
        'cancelled'
    ])->default('pending');
            $table->timestamps();
        });


          Schema::table('payments', function (Blueprint $table) {
                  $table->foreignId('purchase_id')
                  ->nullable()
        ->constrained()
        ->cascadeOnDelete();
           });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};