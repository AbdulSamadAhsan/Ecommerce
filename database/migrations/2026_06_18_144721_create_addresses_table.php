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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
             $table->foreignId('customer_id')
        ->constrained()
        ->cascadeOnDelete();

  

    $table->string('country')
        ->default('Pakistan');

    $table->string('province');

    $table->string('city');

    $table->string('postal_code')
        ->nullable();

    $table->text('address_line_1');

    $table->text('address_line_2')
        ->nullable();

    $table->boolean('is_default')
        ->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};