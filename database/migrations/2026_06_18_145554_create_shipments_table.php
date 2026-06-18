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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
              $table->foreignId('order_id')
        ->constrained()
        ->cascadeOnDelete();

   

    $table->string('tracking_number')->unique();

    $table->decimal('shipping_cost', 12, 2)->default(0);

    $table->enum('status', [
        'pending',
        'packed',
        'shipped',
        'in_transit',
        'out_for_delivery',
        'delivered',
        'returned',
        'cancelled'
    ])->default('pending');

    $table->timestamp('shipped_at')->nullable();

    $table->timestamp('delivered_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};