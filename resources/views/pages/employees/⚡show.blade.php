<?php

use Livewire\Component;

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

        $this->employee = [
            'id' => $this->id,
            'name' => 'Ahmed Raza',
            'email' => 'ahmed@example.com',
            'phone' => '03001234567',
            'department' => 'Inventory',
            'designation' => 'Inventory Manager',
            'salary' => 120000,
            'joining_date' => '2026-01-01',
            'address' => 'Karachi',
            'status' => 1,
            'photo' => asset('asset/default-user.png'),
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
                            <strong>Salary</strong><br>
                            Rs {{ number_format($employee['salary']) }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Joining Date</strong><br>
                            {{ $employee['joining_date'] }}
                        </div>

                        <div class="col-md-12 mb-3">
                            <strong>Address</strong><br>
                            {{ $employee['address'] }}
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
                Salary Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <strong>Basic Salary</strong>
                    <p class="mb-0">
                        Rs {{ number_format(100000) }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Allowance</strong>
                    <p class="mb-0 text-success">
                        Rs {{ number_format(15000) }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Deduction</strong>
                    <p class="mb-0 text-danger">
                        Rs {{ number_format(5000) }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Net Salary</strong>
                    <p class="mb-0 fw-bold text-primary">
                        Rs {{ number_format(110000) }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Bank Name</strong>
                    <p class="mb-0">
                        Meezan Bank
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Account Title</strong>
                    <p class="mb-0">
                        Ahmed Raza
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>Account Number</strong>
                    <p class="mb-0">
                        12345678901234
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <strong>IBAN</strong>
                    <p class="mb-0">
                        PK36MEZN0001234567890123
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
