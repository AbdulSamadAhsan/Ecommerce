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
        Schema::table('shifts', function (Blueprint $table) {
                $table->time("end_time")->after("reporting_time");
                $table->decimal("grace_time")->after("end_time");
                  $table->decimal("duration")->after("grace_time");
        }); 
        Schema::table('attendances', function (Blueprint $table) {
                $table->decimal("late_in_minutes")->after("check_in");
                $table->boolean('overtime')->default(false)->after("check_out");
                $table->decimal('overtime_minutes')->default(false)->after("overtime");
        });
        //attendances
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};