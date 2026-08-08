<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('supplier_id');
            $table->string('purchase_no');
            $table->decimal(12, 2)('subtotal')->nullable()->default(null);
            $table->decimal(12, 2)('discount')->default(0.00);
            $table->decimal(12, 2)('tax')->default(0.00);
            $table->decimal(12, 2)('total_amount');
            $table->decimal(12, 2)('paid_amount');
            $table->decimal(12, 2)('due_amount');
            $table->text('notes');
            $table->date('purchase_date');
            $table->string('status');
            $table->string('payment_status')->default('pending');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
