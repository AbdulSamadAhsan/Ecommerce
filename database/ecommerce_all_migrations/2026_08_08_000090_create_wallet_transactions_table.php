<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('wallet_id');
            $table->decimal(12, 2)('amount');
            $table->enum('type', ['CREDIT', 'DEBIT']);
            $table->string('reference_type')->nullable()->default(null);
            $table->bigInteger('reference_id')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
