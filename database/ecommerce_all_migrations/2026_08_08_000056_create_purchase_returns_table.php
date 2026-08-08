<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('warehouse_id')->nullable()->default(null);
            $table->bigInteger('purchase_id');
            $table->enum('status', ['PENDING', 'APPROVED', 'DECLINED']);
            $table->string('return_no');
            $table->decimal(12, 2)('total_amount');
            $table->text('reason')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->bigInteger('supplier_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};
