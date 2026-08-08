<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranties', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('product_id');
            $table->enum('warranty_type', ['MANUFACTURER', 'SELLER', 'EXTENDED']);
            $table->date('start_date');
            $table->date('end_date');
            $table->smallInteger('duration_months');
            $table->enum('status', ['ACTIVE', 'EXPIRED', 'VOID', 'CLAIMED'])->default('active');
            $table->text('terms')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};
