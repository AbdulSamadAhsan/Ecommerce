<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('user_id')->nullable()->default(null);
            $table->bigInteger('department_id')->nullable()->default(null);
            $table->bigInteger('institution_id')->nullable()->default(null);
            $table->bigInteger('education_id')->nullable()->default(null);
            $table->string('father_name');
            $table->date('date_of_birth');
            $table->string('phone')->nullable()->default(null);
            $table->string('designation')->nullable()->default(null);
            $table->date('joining_date')->nullable()->default(null);
            $table->text('address')->nullable()->default(null);
            $table->string('cnic')->nullable()->default(null);
            $table->string('photo')->nullable()->default(null);
            $table->enum('status', ['RETIRED', 'TERMINATED', 'ACTIVE', 'SUSPENDED'])->default('active');
            $table->enum('gender', ['MALE', 'FEMALE'])->default('male');
            $table->enum('marital_status', ['MARRIED', 'SINGLE'])->default('single');
            $table->string('emergency_contact_name')->nullable()->default(null);
            $table->string('emergency_contact_number')->nullable()->default(null);
            $table->enum('employment_type', ['INTERNSHIP', 'CONTRACT', 'PART-TIME', 'PERMANENT'])->default('permanent');
            $table->string('probation_period')->nullable()->default(null);
            $table->string('emergency_contact_relationship')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('bank_name');
            $table->string('account_title');
            $table->string('account_number');
            $table->string('iban')->nullable()->default(null);
            $table->string('branch_name')->nullable()->default(null);
            $table->string('branch_code')->nullable()->default(null);
            $table->string('swift_code')->nullable()->default(null);
            $table->boolean('is_primary')->default(1);
            $table->text('notes')->nullable()->default(null);
            $table->enum('shift', ['MORNING', 'EVENING', 'NIGHT'])->default('morning');
            $table->time('reporting_time')->nullable()->default(null);
            $table->decimal(8, 2)('notice_period')->default(30.00);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
