<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->string('name');
            $table->string('type')->nullable()->default(null);
            $table->string('city')->nullable()->default(null);
            $table->text('address')->nullable()->default(null);
            $table->boolean('status')->default(1);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
