<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_assignments', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('shipment_id');
            $table->bigInteger('delivery_boy_id');
            $table->timestamp('assigned_at')->nullable()->default(null);
            $table->timestamp('picked_at')->nullable()->default(null);
            $table->timestamp('delivered_at')->nullable()->default(null);
            $table->enum('status', ['ASSIGNED', 'PICKED', 'IN_TRANSIT', 'DELIVERED', 'FAILED'])->default('assigned');
            $table->text('remarks')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('failed_reason')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_assignments');
    }
};
