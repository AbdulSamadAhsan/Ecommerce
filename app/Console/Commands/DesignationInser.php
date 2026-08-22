<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class DesignationInser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:designation-insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert designations from Excel file';

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
       

        $designations = [];

        foreach ($rows as $row) {
            $departmentName = trim($row[1] ?? '');
            $jobTitle = trim($row[2] ?? '');

            if (empty($departmentName) || empty($jobTitle)) {
                continue;
            }

            $departmentFound = Department::where(
                'name',
                $departmentName
            )
                ->orderBy('id', 'asc')
                ->first();

            if (!$departmentFound) {
                continue;
            }
             $designatonFound=Designation::where("department_id",$departmentFound->id)
             ->where("name",$jobTitle)->first();
             if($designatonFound){

           
            $designations[] = [
            'department_id'   => $departmentFound->id,
                'department_name' => $departmentFound->name,
                'job_title'       => $jobTitle,
              
            ];
             }

        }

        $this->table(
            [
                'Department ID',
                'Department Name',
                'Department Designation',
                'Designation Insert Id',
            ],
            $designations
        );

        return Command::SUCCESS;
    }
}