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
        Schema::create('warehouse_holidays', function (Blueprint $table) {
            $table->id();
         $table->foreignId('warehouse_id')
        ->constrained()
        ->cascadeOnDelete();
    $table->foreignId('holiday_id')
        ->constrained()
        ->cascadeOnDelete();
           $table->unique([
        'warehouse_id',
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
        Schema::dropIfExists('warehouse_holidays');
    }
};