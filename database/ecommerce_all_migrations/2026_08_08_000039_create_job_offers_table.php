<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_offers', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('job_application_id');
            $table->decimal(10, 2)('salary');
            $table->date('joining_date');
            $table->tinyInteger('probation_months')->default(3);
            $table->tinyInteger('notice_period_days')->default(30);
            $table->enum('status', ['PENDING', 'ACCEPTED', 'DECLINED'])->default('pending');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
