<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('user_id')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->boolean('status')->default(1);
            $table->bigInteger('referral_by')->nullable()->default(null);
            $table->decimal(8, 2)('referral_bonus');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('referral_code')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
