<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('user_id')->nullable()->default(null);
            $table->string('ip_address', 45)->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->enum('status', ['ACTIVE', 'ORDERED', 'ABANDONED', 'CANCELLED'])->default('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
