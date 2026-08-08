<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_boys', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('user_id')->nullable()->default(null);
            $table->string('cnic')->nullable()->default(null);
            $table->string('vehicle_type')->nullable()->default(null);
            $table->string('vehicle_number')->nullable()->default(null);
            $table->boolean('is_available')->default(1);
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'SUSPENDED'])->default('active');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_boys');
    }
};
