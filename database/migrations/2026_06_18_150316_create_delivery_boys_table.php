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
        Schema::create('delivery_boys', function (Blueprint $table) {
            $table->id();
                $table->foreignId('user_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

  

    $table->string('cnic')->nullable()->unique();

    $table->string('vehicle_type')->nullable();

    $table->string('vehicle_number')->nullable();

    $table->boolean('is_available')->default(true);

    $table->enum('status', [
        'active',
        'inactive',
        'suspended'
    ])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_boys');
    }
};