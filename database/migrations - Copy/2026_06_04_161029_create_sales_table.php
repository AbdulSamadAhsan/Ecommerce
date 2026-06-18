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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
                $table->foreignId('customer_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->string('invoice_no')->unique();

    $table->decimal('subtotal', 12, 2);
    $table->decimal('discount', 12, 2)->default(0);
    $table->decimal('tax', 12, 2)->default(0);
    $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

           if (Schema::hasColumn('sales', 'customer_id')) {
        $table->dropConstrainedForeignId('customer_id');
    }


        Schema::dropIfExists('sales');
    }
};