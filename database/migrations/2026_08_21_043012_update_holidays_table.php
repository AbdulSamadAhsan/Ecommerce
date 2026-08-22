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
        Schema::table('holidays', function (Blueprint $table) {
            //

    $table->string('name')->after("id");
      $table->enum('type', [
        'public',
        'company',
        'optional'
    ])->default('public')->after('name');

    $table->enum('scope', [
        'department',
        'warehouse',
        'company',
        'shift',
        'employee'
    ])->default('company');
    $table->text('description')->nullable()->after('type');

    $table->boolean('is_recurring')->default(false)->after("description");
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