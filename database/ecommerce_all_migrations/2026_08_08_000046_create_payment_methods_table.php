<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('customer_id');
            $table->enum('gateway', ['STRIPE', 'PAYPAL', 'JAZZCASH', 'EASYPAISA', 'CARD']);
            $table->string('payment_token');
            $table->string('card_brand', 30)->nullable()->default(null);
            $table->string('last_four', 4)->nullable()->default(null);
            $table->tinyInteger('expiry_month')->nullable()->default(null);
            $table->smallInteger('expiry_year')->nullable()->default(null);
            $table->string('card_holder_name')->nullable()->default(null);
            $table->boolean('is_default')->default(0);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
