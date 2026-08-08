<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_attempts', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('shipment_id');
            $table->bigInteger('delivery_assignment_id')->nullable()->default(null);
            $table->bigInteger('delivery_boy_id')->nullable()->default(null);
            $table->tinyInteger('attempt_number');
            $table->dateTime('attempted_at');
            $table->enum('reason', ['CUSTOMER_UNAVAILABLE', 'CUSTOMER_REFUSED', 'WRONG_ADDRESS', 'ADDRESS_NOT_FOUND', 'PHONE_UNREACHABLE'])->nullable()->default(null);
            $table->enum('status', ['DELIVERED', 'RESCHEDULED', 'FAILED']);
            $table->text('remarks')->nullable()->default(null);
            $table->decimal(10, 7)('latitude')->nullable()->default(null);
            $table->decimal(10, 7)('longitude')->nullable()->default(null);
            $table->string('photo')->nullable()->default(null);
            $table->string('recipient_name')->nullable()->default(null);
            $table->string('recipient_phone', 20)->nullable()->default(null);
            $table->boolean('otp_verified')->default(0);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_attempts');
    }
};
