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

     Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
               $table->string('name');
    $table->string('code')->unique();

    $table->foreignId('employee_id')
          ->nullable()
          ->constrained('employees')
          ->nullOnDelete();

    $table->text('address')->nullable();

    $table->string('phone')->nullable();

    $table->boolean('status')->default(true);
            $table->timestamps();
        });
           Schema::table('products', function (Blueprint $table) {
             if (! Schema::hasColumn('products', 'warehouse_id')) {

        $table->foreignId('warehouse_id')
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
       Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });
        Schema::dropIfExists('warehouses');
    }
};