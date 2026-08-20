<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
class JobPosting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:job-posting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = public_path(
            'ecommerce_departments_all_jobs_completed.xlsx'
        );

        if (!file_exists($filePath)) {
            $this->error('Excel file not found.');

            return Command::FAILURE;
        }

        $sheets = Excel::toArray(null, $filePath);

        // First sheet
        $rows = $sheets[0];

        // Remove heading rows
        array_shift($rows);
            
            $data = [];

         foreach ($rows as $row) {
            $departmentName = trim($row[1] ?? '');
            $jobTitle = trim($row[2] ?? '');
            $jobDescription = trim($row[3] ?? '');
            $responsibilities = trim($row[4] ?? '');
            $formattedresponsiblities = str_replace(';', "\n", $responsibilities);
            $plainresponsiblities= nl2br(e($formattedresponsiblities));
            $plainResponsibilities = trim(html_entity_decode(strip_tags(  $plainresponsiblities)));
            $requirements = trim($row[5] ?? '');
            $formattedrequirements = str_replace(';', "\n",  $requirements);
            $plainrequirements= nl2br(e( $formattedrequirements));
            $plainrequirements = trim(html_entity_decode(strip_tags(  $plainrequirements)));
               $benefits = trim($row[6] ?? '');
            $formattedbenefits = str_replace(',', "\n", $benefits);
            $plainbenefits= nl2br(e(    $formattedbenefits));
           $plainbenefits = trim(html_entity_decode(strip_tags( $plainbenefits)));
             $vaccanies = trim($row[7] ?? '');
               $maximum_salary = trim($row[8] ?? '');
             $minimum_salary = trim($row[9] ?? '');
              $employee_type = trim($row[10] ?? '');
              $work_mode=trim($row[11] ?? '');
              $closing_date=date("Y-m-d",strtotime($row[12]));

                  $data[] = [
            'job_title' =>   $jobTitle,
            'description'=>  $jobDescription,
            "responsiblities"=> $plainresponsiblities,
            "requirements"=> $plainrequirements,
            "benefits"=>$plainbenefits,
            "vacancies"=>$vaccanies,
            "maximum_salary"=>$maximum_salary,
             "minimum_salary"=>$minimum_salary,
             "employment_type"=>$employee_type,
             "work_mode"=>  $work_mode,
             "closing_date"=> $closing_date
        ];

           

        }

 $this->table(
    [
        'Job Title',
        'Description',
        'Responsibilities',
        'Requirements',
        'Benefits',
        'Vacancies',
        'Maximum Salary',
        'Minimum Salary',
        'Employment Type',
        'Work Mode',
        'Closing Date',
    ],
    $data
);
        return Command::SUCCESS;
      
    }
}