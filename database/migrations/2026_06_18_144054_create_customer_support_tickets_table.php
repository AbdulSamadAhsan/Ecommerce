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
        Schema::create('customer_support_tickets', function (Blueprint $table) {
            $table->id();

    $table->string('ticket_no')->unique();

    $table->foreignId('customer_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('order_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->string('subject');

    $table->text('message');

    $table->enum('priority', [
        'low',
        'medium',
        'high',
        'urgent'
    ])->default('medium');

    $table->enum('status', [
        'open',
        'in_progress',
        'resolved',
        'closed'
    ])->default('open');

    $table->foreignId('assigned_to')
        ->nullable()
        ->constrained('employees')
        ->nullOnDelete();

    $table->timestamp('resolved_at')
        ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_support_tickets');
    }
};
