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
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->id();
              $table->foreignId('warranty_id')
        ->constrained()
        ->cascadeOnDelete();
       $table->foreignId('sale_item_id')
        ->constrained()
        ->cascadeOnDelete();
    $table->foreignId('customer_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('received_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->date('claim_date');

    $table->text('issue_description');

    $table->enum('resolution', [
        'pending',
        'repair',
        'replace',
        'refund',
        'rejected'
    ])->default('pending');

    $table->text('resolution_notes')->nullable();

    $table->date('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_claims');
    }
};