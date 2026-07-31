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
        Schema::create('supplier_quotations', function (Blueprint $table) {
            $table->id();

    $table->string('quotation_number')->unique();

    $table->foreignId('supplier_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('warehouse_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('created_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->date('quotation_date');

    $table->date('valid_until')->nullable();

    $table->decimal('subtotal', 15, 2)->default(0);

    $table->decimal('discount', 15, 2)->default(0);

    $table->decimal('tax', 15, 2)->default(0);

    $table->decimal('shipping_cost', 15, 2)->default(0);

    $table->decimal('grand_total', 15, 2)->default(0);

    $table->enum('status', [
        'draft',
        'received',
        'accepted',
        'rejected',
        'expired',
        'converted'
    ])->default('draft');

    $table->text('terms')->nullable();

    $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_quotations');
    }
};