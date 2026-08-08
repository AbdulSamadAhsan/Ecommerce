<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('name');
            $table->decimal(5, 2)('rate');
            $table->enum('type', ['PERCENTAGE', 'FIXED'])->default('percentage');
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('category')->default('sales');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
