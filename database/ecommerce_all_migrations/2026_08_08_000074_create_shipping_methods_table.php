<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('name');
            $table->decimal(12, 2)('cost')->default(0.00);
            $table->integer('estimated_days')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->text('region')->nullable()->default(null);
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('shipping_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
