<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;
use App\Models\SalaryPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
class EmployeeController extends Controller
{
   
public function downloadCnic(Employee $employee)
    {
        $html = view('employees.cnic', compact('employee'))->render();

        $directory = storage_path('app/public/cnic');

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = "employee-{$employee->user->name}-cnic.png";

        $path = $directory.'/'.$filename;

        Browsershot::html($html)
            ->windowSize(1000, 1000)
            ->deviceScaleFactor(2)
            ->save($path);

        return response()->download(
            $path,
            $filename,
            [
                'Content-Type' => 'image/png',
            ]
        )->deleteFileAfterSend();
    }
    public function downloadCard(Employee $employee)
{
    $expiry_date = Carbon::now()->addYears(5)->format('d-F-Y');
$issue_date = Carbon::now()->format('d-F-Y');
    $html = view('employees.card', compact('employee','expiry_date','issue_date'))->render();
$directory = storage_path('app/public/cards');

if (! File::exists($directory)) {
    File::makeDirectory($directory, 0755, true);
}

   $path = $directory."/employee-{$employee->user->name}.png";

    Browsershot::html($html)
        ->windowSize(1000, 1000)
        ->deviceScaleFactor(2)
        ->save($path);

    return response()->download($path)->deleteFileAfterSend();
}


    public function downloadPayslip($employee_id,$salary_id)
    {

   $employee = Employee::findOrFail($employee_id);

    $salaryPayment = SalaryPayment::with([
        'employee',
        'payroll',
    ])
    ->where('employee_id', $employee_id)
    ->where('id', $salary_id)
    ->firstOrFail();

    $month = Carbon::parse($salaryPayment->payroll->month);

    $monthStart = $month->copy()->startOfMonth();
    $monthEnd = $month->copy()->endOfMonth();

    $daysInMonth = $month->daysInMonth;

    $attendance = Attendance::where('employee_id', $employee->id)
        ->whereBetween('attendance_date', [$monthStart, $monthEnd])
        ->get();
        $attendanceMap = $attendance->keyBy(function ($item) {
    return \Carbon\Carbon::parse($item->attendance_date)->format('Y-m-d');
});

$saturdays = 0;
$sundays = 0;
$period = CarbonPeriod::create($monthStart, $monthEnd);
foreach ($period as $date) {

    if ($date->isSaturday()) {
        $saturdays++;
    }

    if ($date->isSunday()) {
        $sundays++;
    }

}

    $presentDays = $attendance->where('status', 'present')->count();

    $leaveDays = $attendance->where('status', 'leave')->count();

    $absentDays = $attendance->where('status', 'absent')->count();

    $lateDays = $attendance->where('status', 'late')->count();

    $halfDays = $attendance->where('status', 'half_day')->count();

    $pdf = Pdf::loadView('payroll.payslip', [
        'employee'       => $employee,
        'salaryPayment'  => $salaryPayment,
        'daysInMonth'    => $daysInMonth,
        'presentDays'    => $presentDays,
        'leaveDays'      => $leaveDays,
        'absentDays'     => $absentDays,
        'lateDays'       => $lateDays,
        'halfDays'       => $halfDays,
        'monthStart'     => $monthStart,
        'monthEnd'       => $monthEnd,
     

'attendanceMap' => $attendanceMap,
    ]);
    
        // Latest paid salary
   

        return $pdf->download(
            "Payslip-{$employee->employee_code}.pdf"
        );
    }


}