<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_for_quotation_items', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('request_for_quotation_id');
            $table->bigInteger('product_id');
            $table->decimal(12, 2)('quantity');
            $table->string('unit', 50)->nullable()->default(null);
            $table->text('specification')->nullable()->default(null);
            $table->text('remarks')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_for_quotation_items');
    }
};
