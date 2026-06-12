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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
                $table->string('title')->nullable();
    $table->text('description')->nullable();
    $table->string('logo')->nullable();
     $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

           Schema::table('products', function (Blueprint $table) {
             
    if (Schema::hasColumn('products', 'brand_id')) {
        $table->dropForeign(['brand_id']);
        $table->dropColumn('brand_id');
    }
        });
        Schema::dropIfExists('brands');
    }
};