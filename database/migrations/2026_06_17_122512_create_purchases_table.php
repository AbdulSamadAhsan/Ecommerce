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

    $table->decimal('subtotal', 12, 2)->nullable();
    $table->decimal('discount', 12, 2)->default(0);
    $table->decimal('tax', 12, 2)->default(0);
    $table->decimal('total_amount', 12, 2);
    $table->decimal('paid_amount',12,2);
    $table->decimal("due_amount",12,2);
     $table->text("notes");
    $table->date('purchase_date');
    $table->string("status");

    $table->string('payment_status')->default('pending');
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