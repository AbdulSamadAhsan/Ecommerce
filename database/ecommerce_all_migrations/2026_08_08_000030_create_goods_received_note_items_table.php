<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_received_note_items', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('goods_received_note_id');
            $table->bigInteger('purchase_order_item_id');
            $table->bigInteger('product_id');
            $table->decimal(12, 2)('ordered_quantity');
            $table->decimal(12, 2)('previously_received_quantity')->default(0.00);
            $table->decimal(12, 2)('received_quantity');
            $table->decimal(12, 2)('accepted_quantity');
            $table->decimal(12, 2)('rejected_quantity')->default(0.00);
            $table->decimal(15, 2)('unit_cost');
            $table->decimal(15, 2)('line_total');
            $table->enum('quality_status', ['ACCEPTED', 'PARTIALLY_ACCEPTED', 'REJECTED'])->default('accepted');
            $table->text('remarks')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_received_note_items');
    }
};
