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
        
        Schema::create('delivery_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shipment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('delivery_assignment_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('delivery_boy_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->unsignedTinyInteger('attempt_number');

            $table->dateTime('attempted_at');
             $table->enum("reason",[
  'customer_unavailable',
                'customer_refused',
                'wrong_address',
                'address_not_found',
                'phone_unreachable'
             ])->nullable();
            $table->enum('status', [
                'delivered',
              
                'rescheduled',
                
                'failed'
            ]);

            $table->text('remarks')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();

            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('photo')->nullable();

            $table->string('recipient_name')->nullable();

            $table->string('recipient_phone', 20)->nullable();

            $table->boolean('otp_verified')->default(false);

            $table->timestamps();

            $table->index('shipment_id');
            $table->index('delivery_boy_id');
            $table->index('attempted_at');
            $table->index('status');

            $table->unique([
                'shipment_id',
                'attempt_number'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_attempts');
    }
};