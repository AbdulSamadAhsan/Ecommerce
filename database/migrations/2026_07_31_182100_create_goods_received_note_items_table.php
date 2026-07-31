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
        Schema::create('goods_received_note_items', function (Blueprint $table) {
            $table->id();
                $table->foreignId('goods_received_note_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('purchase_order_item_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('product_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->decimal('ordered_quantity', 12, 2);

    $table->decimal('previously_received_quantity', 12, 2)
        ->default(0);

    $table->decimal('received_quantity', 12, 2);

    $table->decimal('accepted_quantity', 12, 2);

    $table->decimal('rejected_quantity', 12, 2)
        ->default(0);

    $table->decimal('unit_cost', 15, 2);

    $table->decimal('line_total', 15, 2);

    $table->enum('quality_status', [
        'accepted',
        'partially_accepted',
        'rejected'
    ])->default('accepted');

    $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_received_note_items');
    }
};