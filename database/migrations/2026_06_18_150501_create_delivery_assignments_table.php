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
        Schema::create('delivery_assignments', function (Blueprint $table) {
            $table->id();
              $table->foreignId('shipment_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('delivery_boy_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->timestamp('assigned_at')->nullable();

    $table->timestamp('picked_at')->nullable();

    $table->timestamp('delivered_at')->nullable();

    $table->enum('status', [
        'assigned',
        'picked',
        'in_transit',
        'delivered',
        'failed'
    ])->default('assigned');

    $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_assignments');
    }
};