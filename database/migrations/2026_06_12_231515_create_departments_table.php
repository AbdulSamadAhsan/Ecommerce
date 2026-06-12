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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
             

    $table->string('name')->unique();

    $table->text('description')->nullable();

    $table->boolean('status')->default(true);

    
            $table->timestamps();
        });
        Schema::table('employees', function (Blueprint $table) {

    $table->foreignId('department_id')
        ->nullable()
        ->after('user_id')
        ->constrained()
        ->nullOnDelete();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
        Schema::dropIfExists('departments');
    }
};