<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('sale_id');
            $table->enum('status', ['DRAFT', 'UNPAID', 'PAID', 'PARTIALLY_PAID', 'CANCELLED'])->default('unpaid');
            $table->date('invoice_date');
            $table->date('due_date')->nullable()->default(null);
            $table->text('pdf_path');
            $table->text('notes')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
