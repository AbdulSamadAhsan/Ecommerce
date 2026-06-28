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
               if (!Schema::hasColumn('carts', 'discount')) {
                $table->decimal('discount', 12, 2)
                    ->default(0)->after("ip_address");
                    
            }

            if (!Schema::hasColumn('carts', 'tax')) {
                $table->decimal('tax', 12, 2)
                    ->default(0)
                   
                    ->after('discount');
            }

            if (!Schema::hasColumn('carts', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)
                    ->default(0)
                    ->after('tax');
            }

            if (!Schema::hasColumn('carts', 'total')) {
                $table->decimal('total', 12, 2)
                    ->default(0)
                    ->after('subtotal');
            }

            if (!Schema::hasColumn('carts', 'status')) {
                $table->enum('status', [
                    'active',
                    'ordered',
                    'abandoned',
                    'cancelled'
                ])->default('active')->after('total');
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