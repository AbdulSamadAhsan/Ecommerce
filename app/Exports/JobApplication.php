<?php

namespace App\Exports;

use App\Models\JobApplication as Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class JobApplication implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithEvents
{
    public function collection()
    {
        return Application::with(
            'applicant:id,full_name,gender,father_name,date_of_birth,cnic,email,phone,martial_status'
        )
        ->get()
        ->sortBy(function ($application) {
            return $application->applicant->full_name;
        })
        ->values()
        ->map(function ($application) {
            return [
                'id' => $application->id,
                'name' => $application->applicant->full_name,
                'father_name' => $application->applicant->father_name,

                'date_of_birth' => date(
                    "d-M Y",
                    strtotime($application->applicant->date_of_birth)
                ),

                'cnic' => $application->applicant->cnic,
                'phone' => $application->applicant->phone,
                'email' => $application->applicant->email,
                'gender' => $application->applicant->gender,
                'martial_status' => $application->applicant->martial_status,

                'jobPosting' =>
                    $application->jobPosting->designation->name,

                'jobDepartment' =>
                    $application->jobPosting->department->name,

                'jobworkmode' =>
                    $application->jobPosting->work_mode,

                'employment_type' =>
                    str()->headline(
                        $application->jobPosting->employment_type
                    ),

                'available_from' =>
                    date(
                        "d M Y",
                        strtotime($application->available_from)
                    ),

                'expected_salary' =>
                    $application->expected_salary,

                'applied_date' =>
                    date(
                        "d-M Y",
                        strtotime($application->created_at)
                    ),

                'status' =>
                    str()->headline($application->status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            "Application Id",
            "Applicant Name",
            "Applicant Father Name",
            "Date Of Birth",
            "CNIC",
            "Phone",
            "Email",
            "Gender",
            "Marital Status",
            "Job Applied",
            "Job Department",
            "Job Work Mode",
            "Employment Type",
            "Available From",
            "Expected Salary",
            "Applied Date",
            "Status"
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $highestRow = $event->sheet->getHighestRow();

                $event->sheet
                    ->getDelegate()
                    ->setAutoFilter("A1:Q{$highestRow}");
            },
        ];
    }
}