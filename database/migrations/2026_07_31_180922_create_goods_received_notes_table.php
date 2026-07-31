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
        Schema::create('goods_received_notes', function (Blueprint $table) {
            $table->id();
              $table->string('grn_number')->unique();

    $table->foreignId('purchase_order_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('supplier_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('warehouse_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('received_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->date('received_date');

    $table->string('supplier_invoice_number')->nullable();

    $table->enum('status', [
        'draft',
        'received',
        'partially_received',
        'completed',
        'cancelled'
    ])->default('received');

    $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_received_notes');
    }
};