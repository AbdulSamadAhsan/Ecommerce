<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('code');
            $table->enum('discount_type', ['PERCENTAGE', 'FIXED']);
            $table->decimal(12, 2)('discount_value');
            $table->decimal(12, 2)('minimum_order_amount')->default(0.00);
            $table->decimal(12, 2)('maximum_discount')->nullable()->default(null);
            $table->integer('usage_limit')->nullable()->default(null);
            $table->integer('used_count')->default(0);
            $table->date('start_date');
            $table->date('expiry_date');
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
