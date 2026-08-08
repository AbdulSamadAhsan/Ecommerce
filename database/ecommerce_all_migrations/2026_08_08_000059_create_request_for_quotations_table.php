<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_for_quotations', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('rfq_number');
            $table->bigInteger('purchase_requisition_id');
            $table->bigInteger('warehouse_id');
            $table->bigInteger('created_by');
            $table->date('issue_date');
            $table->date('closing_date');
            $table->enum('status', ['DRAFT', 'PUBLISHED', 'CLOSED', 'CANCELLED', 'COMPLETED'])->default('draft');
            $table->text('terms_and_conditions')->nullable()->default(null);
            $table->text('remarks')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_for_quotations');
    }
};
