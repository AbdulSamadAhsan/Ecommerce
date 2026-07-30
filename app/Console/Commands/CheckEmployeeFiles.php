<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;
use Carbon\Carbon;
class CheckEmployeeFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-employee-files';

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
        // Change this path if your files are in another folder
        $directory = public_path("employees_cards");

        // Get all files recursively
        $files = File::allFiles($directory);

        $this->info("Searching employee files...\n");

        $employees = Employee::with('user')->get()->sortBy('user.name');

        foreach ($employees as $employee) {

            $found = false;

            foreach ($files as $file) {

                $filename = strtolower($file->getFilename());
                $employeeName = strtolower($employee->user->name);

                
                if (str_contains($filename, $employeeName)) {

                    $this->info("✔ {$employee->name} -> {$file->getRelativePathname()}");
                    $found = true;
                    break;
                }
            }

            if (! $found) {

$expiry_date = Carbon::now()->addYears(5)->format('d-F-Y');
$issue_date = Carbon::now()->format('d-F-Y');
$html = view('employees.cnic', compact('employee','expiry_date',"issue_date"))->render();
$directory =  public_path("employees_cards");
$path = $directory."/employee-{$employee->user->name}.png";

    Browsershot::html($html)
        ->windowSize(1000, 1000)
        ->deviceScaleFactor(2)
        ->save($path);

                $this->line(" {$employee->user->name} -> File Upload found");
            }
        }

        return Command::SUCCESS;
    }
}