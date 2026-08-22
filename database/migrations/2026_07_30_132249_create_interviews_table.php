<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
  $table->foreignId('job_application_id')
        ->constrained()
        ->cascadeOnDelete();



    $table->dateTime('scheduled_at');

    $table->enum('mode',[
        'online',
        'physical',
        'phone'
    ]);
   $table->enum('type',[
        'technical',
        'hr'
    ]);
    $table->string('meeting_link')->nullable();

    $table->enum('status',[
        'scheduled',
        'completed',
        'cancelled',
        'delayed'
    ])->default('Scheduled');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};