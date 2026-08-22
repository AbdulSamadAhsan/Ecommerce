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
        Schema::create('shift_holidays', function (Blueprint $table) {
            $table->id();
            
    $table->foreignId('shift_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('holiday_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->unique([
        'shift_id',
        'holiday_id'
    ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_holidays');
    }
};