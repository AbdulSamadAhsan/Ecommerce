<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Department;
class ReadExcelFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
  protected $signature = 'excel:read';

    protected $description = 'Display department names and job titles';


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

        // First sheet: Departments & Jobs
        $rows = $sheets[0];

        // Remove heading row
        array_shift($rows);

        $data = [];
$departmentsAdded = [];

foreach ($rows as $row) {

    $departmentName = trim($row[1] ?? '');
    $jobTitle = trim($row[2] ?? '');

    if (empty($departmentName) || empty($jobTitle)) {
        continue;
    }

    $departmentFound = Department::where('name', $departmentName)->exists();

    if (
        !$departmentFound &&
        !in_array($departmentName, $departmentsAdded)
    ) {

        $department = Department::firstOrCreate([
        'name' => $departmentName,
    ]);

        $data[] = [
            'name' => $departmentName,
            'id'=>$department->id,
        ];

        $departmentsAdded[] = $departmentName;
    }
}


        $this->table(
            ['Department Name','Department Id'],
            $data
        );

        return Command::SUCCESS;
    }
}