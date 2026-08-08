<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_return_payments', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('purchase_return_id');
            $table->bigInteger('supplier_id');
            $table->decimal(12, 2)('amount');
            $table->enum('status', ['APPROVED', 'PENDING']);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_return_payments');
    }
};
