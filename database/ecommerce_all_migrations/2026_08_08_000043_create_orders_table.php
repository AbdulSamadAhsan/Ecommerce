<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('sale_id');
            $table->text('address');
            $table->enum('order_status', ['PENDING', 'CONFIRMED', 'PROCESSING', 'SHIPPED', 'DELIVERED', 'CANCELLED', 'RETURNED'])->default('pending');
            $table->date('order_date');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('coupon_code')->nullable()->default(null);
            $table->string('cancellation_reason')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
            $table->string('city')->default('Karachi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
