<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('user_id')->nullable()->default(null);
            $table->string('company_name')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->text('address')->nullable()->default(null);
            $table->decimal(15, 2)('opening_balance')->default(0.00);
            $table->boolean('status')->default(1);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->timestamp('deleted_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
