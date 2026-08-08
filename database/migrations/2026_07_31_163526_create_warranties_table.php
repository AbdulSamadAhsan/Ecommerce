<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    
    public function up(): void
    {
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
         

    $table->foreignId('product_id')
        ->constrained()
        ->cascadeOnDelete();

 




    $table->enum('warranty_type', [
        'manufacturer',
        'seller',
        'extended'
    ]);

    $table->date('start_date');

    $table->date('end_date');

    $table->unsignedSmallInteger('duration_months');

    $table->enum('status', [
        'active',
        'expired',
        'void',
        'claimed'
    ])->default('active');

    $table->text('terms')->nullable();

    $table->timestamps();

    $table->index('status');
    $table->index('end_date');
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};