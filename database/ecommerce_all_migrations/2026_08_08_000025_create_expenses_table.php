<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('expense_category_id');
            $table->decimal(12, 2)('amount');
            $table->date('expense_date');
            $table->string('payment_method');
            $table->string('status');
            $table->text('description')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
