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
        Schema::table('customers', function (Blueprint $table) {
            //
                $table->foreignId('referral_by')
                ->nullable()
                ->after('status') // Change the position if needed
                ->constrained('customers')
                ->nullOnDelete();
               $table->string("referral_code")->nullable();           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //

             
            $table->dropForeign(['referral_by']);
            $table->dropColumn('referral_by');
        
        });
    }
};