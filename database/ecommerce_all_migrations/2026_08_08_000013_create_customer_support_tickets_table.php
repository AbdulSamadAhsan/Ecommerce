<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_support_tickets', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('ticket_no');
            $table->bigInteger('customer_id');
            $table->bigInteger('order_id')->nullable()->default(null);
            $table->string('subject');
            $table->text('message');
            $table->enum('priority', ['LOW', 'MEDIUM', 'HIGH', 'URGENT'])->default('medium');
            $table->enum('status', ['OPEN', 'IN_PROGRESS', 'RESOLVED', 'CLOSED'])->default('open');
            $table->bigInteger('assigned_to')->nullable()->default(null);
            $table->timestamp('resolved_at')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_support_tickets');
    }
};
