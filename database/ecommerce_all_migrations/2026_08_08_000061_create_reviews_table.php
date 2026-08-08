<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('product_id');
            $table->bigInteger('customer_id');
            $table->tinyInteger('rating');
            $table->text('review');
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('pending');
            $table->boolean('is_approved')->default(0);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->bigInteger('sale_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
