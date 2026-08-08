<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_portfolios', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('job_application_id');
            $table->string('portfolio_website');
            $table->string('github');
            $table->string('linkedin');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_portfolios');
    }
};
