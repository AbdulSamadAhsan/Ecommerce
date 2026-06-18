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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
$table->string('last_name')->nullable();

$table->string('email')->nullable()->unique();
$table->string('phone')->nullable();

$table->string('designation')->nullable();
$table->string('department')->nullable();

$table->date('joining_date')->nullable();

$table->decimal('salary', 15, 2)->nullable();

$table->text('address')->nullable();

$table->string('cnic')->nullable()->unique();

$table->string('photo')->nullable();

$table->boolean('status')->default(true);

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');
        });

        Schema::dropIfExists('employees');
    }
};