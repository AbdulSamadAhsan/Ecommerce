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
         Schema::create('institutions', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('type')->nullable();
        $table->string('city')->nullable();
        $table->text('address')->nullable();
        $table->boolean('status')->default(true);
        $table->timestamps();
    });

    Schema::create('educations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('institution_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->string('name');
        
        $table->string('status');
        $table->string('short_code')->unique();
        $table->timestamps();
    });

    Schema::table('employees', function (Blueprint $table) {
        if (!Schema::hasColumn('employees', 'institution_id')) {
            $table->foreignId('institution_id')
                ->nullable()
                ->after('department_id')
                ->constrained('institutions')
                ->nullOnDelete();
        }

        if (!Schema::hasColumn('employees', 'education_id')) {
            $table->foreignId('education_id')
                ->nullable()
                ->after('institution_id')
                ->constrained('educations')
                ->nullOnDelete();
        }
    });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_eduction');
    }
};