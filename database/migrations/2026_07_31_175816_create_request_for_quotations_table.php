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
        Schema::create('request_for_quotations', function (Blueprint $table) {
            $table->id();
                $table->string('rfq_number')->unique();

    $table->foreignId('purchase_requisition_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('warehouse_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('created_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->date('issue_date');

    $table->date('closing_date');

    $table->enum('status', [
        'draft',
        'published',
        'closed',
        'cancelled',
        'completed'
    ])->default('draft');

    $table->text('terms_and_conditions')->nullable();

    $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_for_quotations');
    }
};