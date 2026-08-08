<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('customer_id');
            $table->string('country')->default('Pakistan');
            $table->string('province');
            $table->string('city');
            $table->string('postal_code')->nullable()->default(null);
            $table->text('address_line_1');
            $table->text('address_line_2')->nullable()->default(null);
            $table->boolean('is_default')->default(0);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
