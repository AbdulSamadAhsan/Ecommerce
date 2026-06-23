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
        Schema::table('employees', function (Blueprint $table) {
         
           

            $table->string('bank_name');
            $table->string('account_title');
            $table->string('account_number')->unique();

            $table->string('iban')->nullable();

            $table->string('branch_name')->nullable();
            $table->string('branch_code')->nullable();

            $table->string('swift_code')->nullable();

            $table->boolean('is_primary')
                ->default(true);

            $table->text('notes')
                ->nullable();
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
    }
};