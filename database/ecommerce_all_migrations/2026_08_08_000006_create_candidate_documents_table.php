<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_documents', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('job_application_id');
            $table->enum('document_type', ['RESUME', 'COVER_LETTER', 'NATIONAL_ID', 'PASSPORT', 'DRIVING_LICENSE', 'PHOTOGRAPH', 'DEGREE', 'TRANSCRIPT', 'EXPERIENCE_LETTER', 'CERTIFICATION', 'PORTFOLIO', 'REFERENCE_LETTER', 'POLICE_CLEARANCE', 'MEDICAL_CERTIFICATE', 'VISA', 'WORK_PERMIT', 'OTHER']);
            $table->string('title');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type', 100)->nullable()->default(null);
            $table->bigInteger('file_size')->nullable()->default(null);
            $table->text('remarks')->nullable()->default(null);
            $table->boolean('is_verified')->default(0);
            $table->bigInteger('verified_by')->nullable()->default(null);
            $table->timestamp('verified_at')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_documents');
    }
};
