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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

         

            $table->string('invoice_no')
                ->unique();

       

       

            $table->enum('status', [
                'draft',
                'unpaid',
                'paid',
                'partially_paid',
                'cancelled'
            ])->default('unpaid');

            $table->date('invoice_date');

            $table->date('due_date')
                ->nullable();

            $table->text('notes')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};