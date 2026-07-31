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
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
                $table->string('requisition_number')->unique();

    $table->foreignId('department_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->foreignId('warehouse_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('requested_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('approved_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->date('request_date');

    $table->date('required_date')->nullable();

    $table->enum('priority', [
        'low',
        'medium',
        'high',
        'urgent'
    ])->default('medium');

    $table->enum('status', [
        'draft',
        'pending',
        'approved',
        'rejected',
        'cancelled',
        'converted'
    ])->default('draft');

    $table->text('purpose')->nullable();

    $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisitions');
    }
};