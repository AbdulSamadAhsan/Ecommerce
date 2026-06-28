<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
              $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            // Payment gateway
            $table->enum('gateway', [
                'stripe',
                'paypal',
                'jazzcash',
                'easypaisa',
                'card',
            ]);

            // Token returned by the payment gateway
            $table->string('payment_token')->unique();

            // Card information (safe to store)
            $table->string('card_brand', 30)->nullable();          // Visa, Mastercard
            $table->string('last_four', 4)->nullable();            // 4242
            $table->unsignedTinyInteger('expiry_month')->nullable(); // 1-12
            $table->unsignedSmallInteger('expiry_year')->nullable(); // 2028
            $table->string('card_holder_name')->nullable();

            // Default payment method
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};