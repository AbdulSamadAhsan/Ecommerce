<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('warranty_id');
            $table->bigInteger('sale_item_id');
            $table->bigInteger('customer_id');
            $table->bigInteger('received_by')->nullable()->default(null);
            $table->date('claim_date');
            $table->text('issue_description');
            $table->enum('resolution', ['PENDING', 'REPAIR', 'REPLACE', 'REFUND', 'REJECTED'])->default('pending');
            $table->text('resolution_notes')->nullable()->default(null);
            $table->date('resolved_at')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_claims');
    }
};
