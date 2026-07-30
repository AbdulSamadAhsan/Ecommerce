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



    
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
 $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

      

            $table->string('document_number')->nullable();

         

            $table->string('file');

            $table->date('issue_date')->nullable();

            $table->date('expiry_date')->nullable();


        

            $table->text('remarks')->nullable();

            $table->string('document_type')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};