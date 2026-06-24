<?php

use Livewire\Component;
use App\Models\Payroll;

new class extends Component {
    public $payroll;

    public function mount($id)
    {
        $this->payroll = Payroll::with('employee.user')->findOrFail($id);
    }
};
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Payroll Details</h4>
            <p class="text-muted mb-0">View employee payroll record</p>
        </div>

        <a href="{{ route('payrolls.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                {{ $payroll->employee->user->name ?? ($payroll->employee->name ?? 'N/A') }}
            </h5>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <strong>Payroll No</strong>
                    <p>{{ $payroll->payroll_no }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Employee ID</strong>
                    <p>#{{ $payroll->employee_id }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Month</strong>
                    <p>{{ $payroll->month }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Basic Salary</strong>
                    <p>Rs. {{ number_format($payroll->basic_salary, 2) }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Allowances</strong>
                    <p>Rs. {{ number_format($payroll->allowances, 2) }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Bonus</strong>
                    <p>Rs. {{ number_format($payroll->bonus, 2) }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Overtime</strong>
                    <p>Rs. {{ number_format($payroll->overtime, 2) }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Deductions</strong>
                    <p>Rs. {{ number_format($payroll->deductions, 2) }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Tax</strong>
                    <p>Rs. {{ number_format($payroll->tax, 2) }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Net Salary</strong>
                    <p class="fw-bold text-success">
                        Rs. {{ number_format($payroll->net_salary, 2) }}
                    </p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Payment Method</strong>
                    <p>{{ ucfirst($payroll->payment_method) }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Status</strong>
                    <p>
                        @if ($payroll->status === 'paid')
                            <span class="badge bg-success rounded-pill">Paid</span>
                        @elseif ($payroll->status === 'cancelled')
                            <span class="badge bg-danger rounded-pill">Cancelled</span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                        @endif
                    </p>
                </div>

                <div class="col-md-4 mb-3">
                    <strong>Paid Date</strong>
                    <p>{{ date('d-F-Y', strtotime($payroll->paid_date)) ?? '-' }}</p>
                </div>

                <div class="col-md-12 mb-3">
                    <strong>Remarks</strong>
                    <p>{{ $payroll->remarks ?? '-' }}</p>
                </div>

            </div>
        </div>
    </div>

</div>
