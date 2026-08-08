<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('employee_id');
            $table->date('attendance_date');
            $table->time('check_in')->nullable()->default(null);
            $table->decimal(8, 2)('late_in_minutes');
            $table->time('check_out')->nullable()->default(null);
            $table->boolean('overtime')->default(0);
            $table->decimal(8, 2)('overtime_minutes')->default(0.00);
            $table->text('remarks')->nullable()->default(null);
            $table->enum('status', ['PRESENT', 'ABSENT', 'LATE', 'HALF_DAY', 'LEAVE']);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
