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
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });
        Schema::table('employees', function (Blueprint $table) {

    if (! Schema::hasColumn('employees', 'user_id')) {

        $table->foreignId('user_id')
            ->nullable()
            ->after('id')
            ->constrained()
            ->cascadeOnDelete();

    }

});
Schema::table('suppliers', function (Blueprint $table) {

    if (! Schema::hasColumn('suppliers', 'user_id')) {

        $table->foreignId('user_id')
            ->nullable()
            ->after('id')
            ->constrained()
            ->cascadeOnDelete();

    }

});
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('customers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
         Schema::table('employees', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};