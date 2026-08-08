<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('employee_id');
            $table->string('document_number')->nullable()->default(null);
            $table->string('file');
            $table->date('issue_date')->nullable()->default(null);
            $table->date('expiry_date')->nullable()->default(null);
            $table->text('remarks')->nullable()->default(null);
            $table->string('document_type')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};
