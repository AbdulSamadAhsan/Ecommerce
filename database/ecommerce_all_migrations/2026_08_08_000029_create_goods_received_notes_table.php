<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_received_notes', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('grn_number');
            $table->bigInteger('purchase_order_id');
            $table->bigInteger('supplier_id');
            $table->bigInteger('warehouse_id');
            $table->bigInteger('received_by');
            $table->date('received_date');
            $table->string('supplier_invoice_number')->nullable()->default(null);
            $table->enum('status', ['DRAFT', 'RECEIVED', 'PARTIALLY_RECEIVED', 'COMPLETED', 'CANCELLED'])->default('received');
            $table->text('remarks')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_received_notes');
    }
};
