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
        Schema::table('sales_returns', function (Blueprint $table) {
         $table->enum('status', [
        'approved',
        'declined'
    ]);
        });
        Schema::table('purchase_returns', function (Blueprint $table) {
         $table->enum('status', [
        'approved',
        'declined'
    ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};