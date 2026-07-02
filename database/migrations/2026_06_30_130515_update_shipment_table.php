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
        Schema::table('shipments', function (Blueprint $table) {
             if (!Schema::hasColumn('shipments', 'packed_at')) {
                    $table->dateTime('packed_at')->nullable()->after("delivered_at");
                }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment', function (Blueprint $table) {
            //
        });
    }
};