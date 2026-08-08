<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('job_application_id');
            $table->dateTime('scheduled_at');
            $table->enum('type', ['ONLINE', 'PHYSICAL', 'PHONE']);
            $table->string('meeting_link')->nullable()->default(null);
            $table->enum('status', ['SCHEDULED', 'COMPLETED', 'CANCELLED', 'DELAYED'])->default('scheduled');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
