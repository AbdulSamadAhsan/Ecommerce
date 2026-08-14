<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobApplication;
use App\Models\CandidateDocument;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
class Resume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:resume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List Candidate Who donot have document type resume';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
            $jobApplications = JobApplication::whereDoesntHave('documents', function ($query) {
        $query->where('document_type', 'resume');
    })->get();
foreach ($jobApplications as $application) {
    $this->info($application->full_name);
     $data = [

        'candidate' =>$application->full_name,
        'email' => $application->email,
        'phone' => $application->phone,
        'linkedin' => $application->linkedin,
        'photo' => $application->photo,
        'experiences' =>$application->works,
        'educations' => $application->educations,
        'personal_details' => [
            'father_name' => $application->father_name,
            'dob' => date("d F Y",strtotime($application->date_of_birth)),
             'gender' => $application->gender,
             'cnic' => $application->cnic,
          'address' => $application->address,
          ],
    ];


    $pdf = Pdf::loadView('pdf.resume', $data)
        ->setPaper('a4', 'portrait');

$fileName = 'resume-' . $application->full_name .time(). '.pdf';
    $path = 'documents/' . str()->slug($application->full_name) . '/' . $fileName;

                Storage::disk('public')->put($path, $pdf->output());

                $fullPath = Storage::disk('public')->path($path);
                $mimeType = mime_content_type($fullPath);
                $size = filesize($fullPath);
CandidateDocument::create([
    'job_application_id' => $application->id,
                'document_type' => 'resume',
                'file_name' => "Resume",
                'file_size' => $size,
                'file_path' => 'storage/' . $path,
                'mime_type' => $mimeType,
]);

    
}


    }
}