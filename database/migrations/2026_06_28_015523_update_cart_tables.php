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
        Schema::table('carts', function (Blueprint $table) {
       
            if (Schema::hasColumn('carts', 'session_id')) {
                $table->dropColumn('session_id');
            }
             


         

          

            if (!Schema::hasColumn('carts', 'status')) {
                $table->enum('status', [
                    'active',
                    'ordered',
                    'abandoned',
                    'cancelled'
                ])->default('active');
            }
     //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            //
        });
    }
};