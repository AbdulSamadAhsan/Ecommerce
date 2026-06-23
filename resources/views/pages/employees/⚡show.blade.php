<?php

use Livewire\Component;
use App\Models\Employee;
use App\Models\Education;
new class extends Component {
    public int $id;
    public array $salaryPayments = [];
    public array $employee = [];

    public array $attendance = [];

    public array $leaves = [];

    public array $payrolls = [];

    public function mount($id): void
    {
        $this->id = (int) $id;
        $employeedata = Employee::with(['education', 'department', 'institute', 'user', 'salaryData'])->findOrFail($this->id);

        $this->employee = [
            'id' => $this->id,
            'name' => $employeedata->user->name,
            'email' => $employeedata->user->email,
            'phone' => $employeedata->phone,
            'department' => $employeedata->department->name,
            'designation' => $employeedata->designation,
            'salary' => $employeedata->salary,
            'joining_date' => $employeedata->joining_date,
            'address' => $employeedata->address,
            'status' => $employeedata->status,
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
        ];

        $this->attendance = [
            [
                'date' => '2026-06-18',
                'status' => 'Present',
            ],
            [
                'date' => '2026-06-17',
                'status' => 'Present',
            ],
            [
                'date' => '2026-06-16',
                'status' => 'Absent',
            ],
        ];

        $this->leaves = [
            [
                'type' => 'Sick Leave',
                'from' => '2026-05-10',
                'to' => '2026-05-12',
                'status' => 'Approved',
            ],
        ];

        $this->salaryPayments = [
            [
                'month' => 'May 2026',
                'amount' => 110000,
                'payment_date' => '2026-05-31',
                'method' => 'Bank Transfer',
                'transaction_id' => 'TXN10001',
                'status' => 'Paid',
            ],

            [
                'month' => 'April 2026',
                'amount' => 110000,
                'payment_date' => '2026-04-30',
                'method' => 'Bank Transfer',
                'transaction_id' => 'TXN10002',
                'status' => 'Paid',
            ],
        ];

        $this->payrolls = [
            [
                'month' => 'May 2026',
                'salary' => 120000,
                'status' => 'Paid',
            ],
            [
                'month' => 'April 2026',
                'salary' => 120000,
                'status' => 'Paid',
            ],
        ];
    }
};

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

        <a href="{{ route('employees.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>

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

                        <div class="col-md-6 mb-3">
                            <strong>Email</strong><br>
                            {{ $employee['email'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Phone</strong><br>
                            {{ $employee['phone'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Department</strong><br>
                            {{ $employee['department'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Designation</strong><br>
                            {{ $employee['designation'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Age</strong><br>
                            Rs {{ $employee['age'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Joining Date</strong><br>
                            {{ $employee['joining_date'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Address</strong><br>
                            {{ $employee['address'] }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Father Name</strong><br>
                            {{ $employee['father_name'] }}
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
                        Rs {{ number_format(100000) }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Institute</strong>
                    <p class="mb-0 ">
                        Rs {{ number_format(15000) }}
                    </p>
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
                                {{ $payment['month'] }}
                            </td>

                            <td>
                                Rs {{ number_format($payment['amount']) }}
                            </td>

                            <td>
                                {{ $payment['payment_date'] }}
                            </td>

                            <td>
                                {{ $payment['method'] }}
                            </td>

                            <td>
                                {{ $payment['transaction_id'] }}
                            </td>

                            <td>

                                @if ($payment['status'] === 'Paid')
                                    <span class="badge bg-success">
                                        Paid
                                    </span>
                                @elseif($payment['status'] === 'Pending')
                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Failed
                                    </span>
                                @endif

                            </td>

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

                            <td>{{ $item['date'] }}</td>

                            <td>

                                @if ($item['status'] === 'Present')
                                    <span class="badge bg-success">
                                        Present
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Absent
                                    </span>
                                @endif

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
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($leaves as $leave)
                        <tr>

                            <td>{{ $leave['type'] }}</td>
                            <td>{{ $leave['from'] }}</td>
                            <td>{{ $leave['to'] }}</td>

                            <td>

                                <span class="badge bg-primary">
                                    {{ $leave['status'] }}
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
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($payrolls as $payroll)
                        <tr>

                            <td>{{ $payroll['month'] }}</td>

                            <td>
                                Rs {{ number_format($payroll['salary']) }}
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
