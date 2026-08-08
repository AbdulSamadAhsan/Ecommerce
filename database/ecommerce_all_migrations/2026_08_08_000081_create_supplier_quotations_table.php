<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_quotations', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('quotation_number');
            $table->bigInteger('supplier_id');
            $table->bigInteger('warehouse_id');
            $table->bigInteger('created_by');
            $table->date('quotation_date');
            $table->date('valid_until')->nullable()->default(null);
            $table->decimal(15, 2)('subtotal')->default(0.00);
            $table->decimal(15, 2)('discount')->default(0.00);
            $table->decimal(15, 2)('tax')->default(0.00);
            $table->decimal(15, 2)('shipping_cost')->default(0.00);
            $table->decimal(15, 2)('grand_total')->default(0.00);
            $table->enum('status', ['DRAFT', 'RECEIVED', 'ACCEPTED', 'REJECTED', 'EXPIRED', 'CONVERTED'])->default('draft');
            $table->text('terms')->nullable()->default(null);
            $table->text('remarks')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_quotations');
    }
};
