<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class EmployeeData implements FromCollection,WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::get()->map(function($employee){
          return[
               'code' => $employee->employee_code,
            "name"=>$employee->user->name,
            "father_name"=>$employee->father_name,
            "phone"=>$employee->phone,  
            "age"=>$employee->age,
            "marital_status"=>$employee->marital_status,
            "gender"=>ucfirst($employee->gender),
          
            "cnic"=>$employee->cnic,
            "department"=>$employee->department->name,
            "designation"=>$employee->designation,
               "employment_type"=>$employee->employment_type,
               "probation_period"=>$employee->probation_period." Months",
               "shift"=>$employee->shift,
               "reporting_time"=>date("h:i a",strtotime($employee->reporting_time)),
               "previous_experience_duration"=>$employee->experience_duration,
             'education' => $employee->education?->name,
            'institution' => $employee?->institute?->name,
            "basic_salary"=> $employee->salaryData->basic_salary,
     "allowance"=> $employee->salaryData->allowance? $employee->salaryData->allowance:0,
     "tax_deduction"=>$employee->salaryData->tax_deduction,
       "net_salary"=>$employee->salaryData->net_salary,

          ];
        });
    }
      public function headings(): array
    {
        return[
            "Employee Code",
             "Employee Name",
             "Father Name",
             "Phone",
               "Age",
             "Marital Status",
           
             "Gender",
            
             "Cnic",
             "Department",
             "Designation",
             "Employment Type",
             "Probation Period",
             "Shift",
             "Reporting Time",
             "Previous Experience",
             "Education",
             "Institution",
             "Basic Salary",
             "Allowance",
             "Tax Deduction",
             "Net Salary"
        ];
    }
}