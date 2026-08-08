<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('po_number');
            $table->bigInteger('supplier_id');
            $table->bigInteger('warehouse_id');
            $table->bigInteger('created_by');
            $table->bigInteger('approved_by')->nullable()->default(null);
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable()->default(null);
            $table->decimal(15, 2)('subtotal')->default(0.00);
            $table->decimal(15, 2)('discount')->default(0.00);
            $table->decimal(15, 2)('tax')->default(0.00);
            $table->decimal(15, 2)('shipping_cost')->default(0.00);
            $table->decimal(15, 2)('grand_total')->default(0.00);
            $table->enum('status', ['DRAFT', 'PENDING_APPROVAL', 'APPROVED', 'SENT', 'PARTIALLY_RECEIVED', 'COMPLETED', 'CANCELLED'])->default('draft');
            $table->text('notes')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
