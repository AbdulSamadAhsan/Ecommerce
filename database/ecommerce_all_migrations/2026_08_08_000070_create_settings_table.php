<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('max_cash_order_amount');
            $table->string('cancellation_penalty');
            $table->string('cancellation_window');
            $table->decimal(8, 2)('late_penalty');
            $table->decimal(8, 2)('referral_bonus');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
