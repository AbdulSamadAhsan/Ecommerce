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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
                $table->string('po_number')->unique();

    $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();

    $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();

    $table->foreignId('created_by')->constrained('users');

    $table->foreignId('approved_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->date('order_date');

    $table->date('expected_delivery_date')->nullable();

    $table->decimal('subtotal',15,2)->default(0);

    $table->decimal('discount',15,2)->default(0);

    $table->decimal('tax',15,2)->default(0);

    $table->decimal('shipping_cost',15,2)->default(0);

    $table->decimal('grand_total',15,2)->default(0);

    $table->enum('status',[
        'draft',
        'pending_approval',
        'approved',
        'sent',
        'partially_received',
        'completed',
        'cancelled'
    ])->default('draft');

    $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};