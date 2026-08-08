<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('customer_support_ticket_id');
            $table->text('message');
            $table->enum('message_by', ['ADMIN', 'CUSTOMER'])->default('customer');
            $table->string('attachment')->nullable()->default(null);
            $table->boolean('is_internal')->default(0);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};
