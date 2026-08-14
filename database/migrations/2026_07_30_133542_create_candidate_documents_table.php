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
        Schema::create('candidate_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_application_id')
                ->constrained()
                ->cascadeOnDelete();

         $table->enum('document_type', [
    'resume',
    'cover_letter',
    'national_id',
    'passport',
    'driving_license',
    'photograph',
    'degree',
    'transcript',
    'experience_letter',
    'certification',
    'portfolio',
    'reference_letter',
    'police_clearance',
    'medical_certificate',
    'visa',
    'work_permit',
    'other',
]);
            /*
                Resume
                Cover Letter
                CNIC
                Passport
                Degree
                Transcript
                Experience Letter
                Certification
                Portfolio
                Photograph
                Other
            */

         

            $table->string('file_name');

            $table->string('file_path');

            $table->string('mime_type', 100)->nullable();

            $table->unsignedBigInteger('file_size')->nullable(); // bytes

            $table->text('remarks')->nullable();

            $table->boolean('is_verified')->default(false);

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
               $table->index('job_application_id');
            $table->index('document_type');
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_documents');
    }
};