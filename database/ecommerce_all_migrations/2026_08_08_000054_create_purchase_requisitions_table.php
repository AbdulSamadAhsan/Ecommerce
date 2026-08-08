<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('requisition_number');
            $table->bigInteger('department_id')->nullable()->default(null);
            $table->bigInteger('warehouse_id');
            $table->bigInteger('requested_by');
            $table->bigInteger('approved_by')->nullable()->default(null);
            $table->date('request_date');
            $table->date('required_date')->nullable()->default(null);
            $table->enum('priority', ['LOW', 'MEDIUM', 'HIGH', 'URGENT'])->default('medium');
            $table->enum('status', ['DRAFT', 'PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'CONVERTED'])->default('draft');
            $table->text('purpose')->nullable()->default(null);
            $table->text('remarks')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_requisitions');
    }
};
