<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\SalaryPayment;
use App\Exports\EmployeeData;
use Maatwebsite\Excel\Facades\Excel;
class ReportController extends Controller
{
    public function report(Employee $employee)
{
    $currentMonth = now();

    $attendance = Attendance::where('employee_id', $employee->id)
        ->whereMonth('attendance_date', $currentMonth->month)
        ->whereYear('attendance_date', $currentMonth->year)
        ->get();

    $salaryPayments = SalaryPayment::where('employee_id', $employee->id)
        ->whereMonth('created_at', '>=', now()->subYear()->startOfMonth())
    
        ->get();

    $leaveRecords = Leave::where('employee_id', $employee->id)->get();

    $payrolls = Payroll::where('employee_id', $employee->id)
        ->latest()
        ->get();

    return Pdf::loadView('pdf.employee-report', compact(
        'employee',
        'attendance',
        'salaryPayments',
        'leaveRecords',
        'payrolls'
    ))->download('employee-report.pdf');
}
   public function allemployee(){
    return Excel::download(new EmployeeData(),"employee-report.xlsx");
   }
}