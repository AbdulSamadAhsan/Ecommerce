<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('transfer_no');
            $table->bigInteger('from_warehouse_id');
            $table->bigInteger('to_warehouse_id');
            $table->enum('status', ['PENDING', 'APPROVED', 'IN_TRANSIT', 'COMPLETED', 'CANCELLED'])->default('pending');
            $table->text('remarks')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
