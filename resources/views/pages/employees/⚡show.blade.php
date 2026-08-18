<?php

use Livewire\Component;
use App\Models\Employee;
use App\Models\Education;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\SalaryPayment;
new class extends Component {
    public int $id;
    public $salaryPayments;
    public array $employee = [];

    public array $attendance = [];

    public array $leaves = [];

    public array $payrolls = [];
    public $leaveCount;
    public $lateCount;
    public $total_presence;
    public $salaryPaymentUnpaid;
    public $salaryPaymentpaid;
    public $remainingMonths;
    public function mount($id): void
    {
        $this->id = (int) $id;
        $employeedata = Employee::with(['education', 'department', 'institute', 'user', 'salaryData', 'salaryPayments', 'payroll', 'attendance', 'leave'])->findOrFail($this->id);
        /*   dd($employeedata->toArray());*/
        $this->salaryPaymentUnpaid = \App\Models\SalaryPayment::where('employee_id', $id)->where('status', 'pending')->sum('amount');
        $this->salaryPaymentpaid = \App\Models\SalaryPayment::where('employee_id', $id)->where('status', 'paid')->sum('amount');
        $this->leaveCount = $employeedata->attendance->where('status', 'leave')->count();
        $this->lateCount = $employeedata->attendance->where('status', 'late')->count();
        $this->employee = [
            'id' => $this->id,
            'name' => $employeedata->user->name,
            'email' => $employeedata->user->email,
            'phone' => $employeedata->phone,
            'code' => $employeedata->employee_code,
            'department' => $employeedata->department->name,
            'designation' => $employeedata->designation,
            'salary' => $employeedata->salaryData->basic_salary,
            'joining_date' => $employeedata->joining_date,
            'address' => $employeedata->address,
            'status' => $employeedata->status,
            'cnic' => $employeedata->cnic,
            'gender' => $employeedata->gender,
            'education' => $employeedata->education?->name,
            'institution' => $employeedata?->institute?->name,
            'photo' => asset('storage/' . $employeedata->photo),
            'education' => $employeedata->education?->name,
            'allowance' => $employeedata->salaryData->allowance,
            'tax_deduction' => $employeedata->salaryData->tax_deduction,
            'net_salary' => $employeedata->salaryData->net_salary,
            'annual_salary' => $employeedata->annual_salary,
            'age' => $employeedata->age,
            'father_name' => $employeedata->father_name,
            'account_number' => $employeedata->account_number,
            'account_title' => $employeedata->account_title,
            'bank_name' => $employeedata->bank_name,
            'iban' => $employeedata->iban,
            'employment_type' => $employeedata->employment_type,
            'probation_period' => $employeedata->probation_period,
            'shift' => $employeedata->shift,
            'unpaid_salary' => $this->salaryPaymentUnpaid,
            'paid_salary' => $this->salaryPaymentpaid,
            'reporting_time' => date('h:i a', strtotime($employeedata->reporting_time)),
        ];

        $this->attendance = $employeedata->attendance->toArray();
        $this->leaves = $employeedata->leave->toArray();
        $this->total_presence = $employeedata->attendance->where('status', 'present')->count();
        $this->salaryPayments = $employeedata->salaryPayments;
        $this->payrolls = $employeedata->payroll->toArray();

        $joiningDate = Carbon::parse($employeedata->joining_date)->startOfMonth();
        $currentDate = now()->startOfMonth();

        // Total months from joining till now (inclusive)
        $totalMonths = $joiningDate->diffInMonths($currentDate) + 1;
        // Paid salary records
        $paidMonths = SalaryPayment::where('employee_id', $employeedata->id)->count();

        // Missing salary records
        $missingMonths = max(0, $totalMonths - $paidMonths);

        $start = Carbon::parse($employeedata->joining_date)->startOfMonth();
        $end = now()->startOfMonth();

        $expectedMonths = collect();

        foreach (CarbonPeriod::create($start, '1 month', $end) as $month) {
            $expectedMonths->push($month->format('Y-m'));
        }

        $paidMonths = SalaryPayment::where('employee_id', $employeedata->id)->with('payroll:id,month')->get()->pluck('payroll.month')->filter()->map(fn($month) => Carbon::parse($month)->format('Y-m'));

        $this->remainingMonths = $expectedMonths->diff($paidMonths);
    }
};
///
?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold">
                Employee Details
            </h3>

            <p class="text-muted">
                Employee profile, attendance, leaves and payroll
            </p>

        </div>
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('employees.report', $employee['id']) }}" class="btn rounded-pill btn-danger">
                    Report
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('employees.index') }}" class="btn btn-secondary rounded-pill">
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Presence</h6>
                    <h3 class="fw-bold text-primary">{{ $total_presence }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Leave</h6>
                    <h3 class="fw-bold text-danger">{{ $leaveCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Late</h6>
                    <h3 class="fw-bold text-info"> {{ $lateCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <img src="{{ $employee['photo'] }}" width="120" class="rounded-circle mb-3">

                    <h5 class="fw-bold">
                        {{ $employee['name'] }}
                    </h5>

                    <p class="text-muted">
                        {{ $employee['designation'] }}
                    </p>

                </div>

            </div>

        </div>

        <div class="col-md-9">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <strong>Email</strong><br>
                            {{ $employee['email'] }}
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Phone</strong><br>
                            {{ $employee['phone'] }}
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>CNIC</strong><br>
                            {{ $employee['cnic'] }}

                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Department</strong><br>
                            {{ $employee['department'] }}
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Designation</strong><br>
                            {{ $employee['designation'] }}
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Employee Code</strong><br>
                            {{ $employee['code'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Age</strong><br>
                            Rs {{ $employee['age'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Joining Date</strong><br>
                                    {{ date('d-M-Y', strtotime($employee['joining_date'])) }}
                                </div>
                                @php
                                    $diff = \Carbon\Carbon::parse($employee['joining_date'])->diff(now());
                                @endphp


                                <div class="col-md-6">
                                    <strong>Year Of Employment</strong><br>
                                    {{ $diff->y }} Years {{ $diff->m }} Months {{ $diff->d }} Days

                                </div>
                            </div>


                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Address</strong><br>
                            {{ $employee['address'] }}
                        </div>
                        <div class="col-md-3 mb-3">
                            <strong>Father Name</strong><br>
                            {{ $employee['father_name'] }}
                        </div>
                        <div class="col-md-3 mb-3">
                            <strong>Gender</strong><br>
                            {{ ucfirst($employee['gender']) }}
                        </div>

                        <div class="col-md-3 mb-3">
                            <strong>Employment Type</strong><br>
                            {{ ucfirst($employee['employment_type']) }}
                        </div>
                        <div class="col-md-3 mb-3">
                            <strong>Probation Period</strong><br>
                            {{ ucfirst($employee['probation_period']) }} Month
                        </div>
                        <div class="col-md-3 mb-3">
                            <strong>Shift</strong><br>
                            {{ ucfirst($employee['shift']) }}
                        </div>
                        <div class="col-md-3 mb-3">
                            <strong>Reporting Time</strong><br>
                            {{ date('h:i a', strtotime($employee['reporting_time'])) }}
                        </div>
                        <div class="col-md-6">

                            <strong>Status</strong><br>

                            @if ($employee['status'])
                                <span class="badge bg-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    Inactive
                                </span>
                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                Education Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Education</strong>
                    <p class="mb-0">
                        {{ $employee['education'] }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Institute</strong>
                    <p class="mb-0 ">
                        {{ $employee['institution'] }}
                    </p>
                </div>





            </div>

        </div>

    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">

                    <a href="{{ route('employees.card.download', $employee['id']) }}" class="btn btn-success">
                        Employee Card
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('employees.cnic.download', $employee['id']) }}" class="btn btn-success">
                        CNIC
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                Salary Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <strong>Basic Salary</strong>
                    <p class="mb-0">
                        Rs {{ $employee['salary'] }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Allowance</strong>
                    <p class="mb-0 text-success">
                        Rs {{ $employee['allowance'] }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Tax Deduction</strong>
                    <p class="mb-0 text-danger">
                        Rs {{ $employee['tax_deduction'] }}
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>Total Unpaid Salary</strong>
                    <p class="mb-0 text-danger">
                        Rs {{ $employee['unpaid_salary'] }}
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>Total Paid Salary</strong>
                    <p class="mb-0 text-success">
                        Rs {{ $employee['paid_salary'] }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Net Salary</strong>
                    <p class="mb-0 fw-bold text-primary">
                        Rs {{ $employee['net_salary'] }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Bank Name</strong>
                    <p class="mb-0">
                        {{ $employee['bank_name'] }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Account Title</strong>
                    <p class="mb-0">
                        {{ $employee['account_title'] }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Account Number</strong>
                    <p class="mb-0">
                        {{ $employee['account_number'] }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>IBAN</strong>
                    <p class="mb-0">
                        {{ $employee['iban'] }}
                    </p>
                </div>

            </div>

        </div>

    </div>



    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">

            <h5 class="mb-0">
                Salary Payment History
            </h5>

        </div>

        <div class="card-body table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>Month</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Method</th>
                        <th>Transaction ID</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($salaryPayments as $payment)
                        <tr>

                            <td>
                                {{ date('M-Y', strtotime($payment['payroll']['month'])) }}
                            </td>


                            <td>
                                Rs {{ number_format($payment['amount']) }}
                            </td>

                            <td>
                                @if ($payment['status'] == 'paid')
                                    {{ $payment['paid_date'] }}
                                @else
                                    Salary Not Paid Yet
                                @endif
                            </td>

                            <td>
                                {{ $payment['payment_method'] }}
                            </td>

                            <td>
                                {{ $payment['transaction_id'] }}
                            </td>

                            <td>

                                @if ($payment['status'] === 'paid')
                                    <span class="badge bg-success">
                                        Paid
                                    </span>
                                @elseif($payment['status'] === 'pending')
                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Failed
                                    </span>
                                @endif

                            </td>
                            <td><a
                                    href="{{ route('employees.payslip.download', [
                                        'employee' => $payment->employee_id,
                                        'salary_payment_id' => $payment->id,
                                    ]) }}">
                                    Download Payslip
                                </a></td>
                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center text-muted">
                                No salary payments found.
                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">

            <h5 class="mb-0">
                Unrecorded Salary Payment History
            </h5>

        </div>

        <div class="card-body table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>Month</th>
                        <td>Year</td>

                    </tr>

                </thead>

                <tbody>

                    @forelse($remainingMonths as $payment)
                        <tr>
                            <td>{{ date('F', strtotime($payment)) }}</td>
                            <td>{{ date('Y', strtotime($payment)) }}</td>
                        </tr>
                    @empty

                        <tr>

                            <td colspan="6" class="text-center text-muted">
                                No salary payments found.
                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                Attendance History
            </h5>
        </div>

        <div class="card-body table-responsive">

            <table class="table">

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($attendance as $item)
                        <tr>

                            <td>{{ $item['attendance_date'] }}</td>

                            <td>
                                @switch($item['status'])
                                    @case('present')
                                        <span class="badge bg-success">Present</span>
                                    @break

                                    @case('absent')
                                        <span class="badge bg-danger">Absent</span>
                                    @break

                                    @case('leave')
                                        <span class="badge bg-warning">Leave</span>
                                    @break

                                    @case('half_day')
                                        <span class="badge bg-info">Half Day</span>
                                    @break

                                    @case('late')
                                        <span class="badge bg-primary">Late</span>
                                    @break

                                    @case('holiday')
                                        <span class="badge bg-secondary">Holiday</span>
                                    @break

                                    @default
                                        <span
                                            class="badge bg-dark">{{ ucfirst(str_replace('_', ' ', $item['status'])) }}</span>
                                @endswitch

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                Leave History
            </h5>
        </div>

        <div class="card-body table-responsive">

            <table class="table">

                <thead>
                    <tr>
                        <th>Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Number oF Days</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($leaves as $leave)
                        <tr>

                            <td>{{ $leave['leave_type'] }}</td>
                            <td>{{ $leave['from_date'] }}</td>
                            <td>{{ $leave['to_date'] }}</td>
                            <td>{{ $leave['days'] }}</td>

                            <td>

                                <span class="badge bg-primary">
                                    {{ ucwords($leave['status']) }}
                                </span>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <div class="card border-0 shadow">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                Payroll History
            </h5>
        </div>

        <div class="card-body table-responsive">

            <table class="table">

                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Salary</th>
                        <th>Allowance</th>
                        <th>Bonus</th>
                        <th>Overtime</th>

                        <th>Tax</th>
                        <th>Deduction</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($payrolls as $payroll)
                        <tr>

                            <td>{{ $payroll['month'] }}</td>

                            <td>
                                Rs {{ number_format($payroll['basic_salary']) }}
                            </td>

                            <td>
                                Rs {{ number_format($payroll['allowances']) }}
                            </td>
                            <td>
                                Rs {{ number_format($payroll['bonus']) }}
                            </td>
                            <td>
                                Rs {{ number_format($payroll['overtime']) }}
                            </td>
                            <td>
                                Rs {{ number_format($payroll['tax']) }}
                            </td>
                            <td>
                                Rs {{ number_format($payroll['deductions']) }}
                            </td>
                            <td>
                                Rs {{ number_format($payroll['net_salary']) }}
                            </td>
                            <td>

                                <span class="badge bg-success">
                                    {{ $payroll['status'] }}
                                </span>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>
