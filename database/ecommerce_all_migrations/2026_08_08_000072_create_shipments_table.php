<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('order_id');
            $table->bigInteger('shipping_method_id')->nullable()->default(null);
            $table->string('tracking_number');
            $table->enum('status', ['PENDING', 'PACKED', 'SHIPPED', 'IN_TRANSIT', 'OUT_FOR_DELIVERY', 'DELIVERED', 'RETURNED', 'CANCELLED'])->default('pending');
            $table->timestamp('shipped_at')->nullable()->default(null);
            $table->timestamp('delivered_at')->nullable()->default(null);
            $table->dateTime('packed_at')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->dateTime('expected_delivery')->nullable()->default(null);
            $table->dateTime('cancelled_at')->nullable()->default(null);
            $table->bigInteger('dispatch_by')->nullable()->default(null);
            $table->bigInteger('canceled_by')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
