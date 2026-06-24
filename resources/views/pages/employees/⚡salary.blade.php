<?php

use Livewire\Component;
use App\Models\Employee;
use App\Models\SalaryPayment;
use App\Models\Salary;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\Payroll;

new class extends Component {
    public $employees = [];
    public $salaryPayments = [];

    public $employee_id = '';
    public $month;

    public $basic_salary = 0;
    public $allowances = 0;
    public $bonus = 0;
    public $overtime = 0;
    public $deductions = 0;
    public $tax = 0;
    public $net_salary = 0;

    public $payment_method = 'cash';
    public $status = 'pending';
    public $paid_date;

    public $editId = null;

    public function mount()
    {
        $this->month = now()->format('Y-m');
        $this->paid_date = now()->format('Y-m-d');

        $this->employees = Employee::with('user')->where('status', 1)->latest()->get();

        $this->loadPayments();
    }

    public function updatedEmployeeId()
    {
        $employee = Employee::with('salaryData')->find($this->employee_id);

        $this->basic_salary = $employee->salaryData->basic_salary ?? 0;
        $this->allowances = $employee->salaryData->allowance ?? 0;
        $this->tax = $employee->salaryData->tax_deduction ?? 0;

        $this->calculateNetSalary();
    }

    public function updated($property)
    {
        if (in_array($property, ['basic_salary', 'allowances', 'bonus', 'overtime', 'deductions', 'tax'])) {
            $this->calculateNetSalary();
        }
    }

    public function calculateNetSalary()
    {
        $this->net_salary = (float) $this->basic_salary + (float) $this->allowances + (float) $this->bonus + (float) $this->overtime - (float) $this->deductions - (float) $this->tax;
    }

    public function save()
    {
        $this->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required',
            'bonus' => 'nullable|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payment_method' => 'required',
            'status' => 'required|in:pending,paid',
            'paid_date' => 'nullable|date',
        ]);

        $salary = Salary::where('employee_id', $this->employee_id)->first();

        if (!$salary) {
            session()->flash('error', 'Salary record not found for this employee.');
            return;
        }

        $this->basic_salary = $salary->basic_salary ?? 0;
        $this->allowances = $salary->allowance ?? 0;
        $this->tax = $salary->tax_deduction ?? 0;

        $this->calculateNetSalary();

        if ($this->editId) {
            $payment = SalaryPayment::findOrFail($this->editId);

            $payroll = Payroll::find($payment->payroll_id);

            if ($payroll) {
                $payroll->update([
                    'employee_id' => $this->employee_id,
                    'month' => $this->month,
                    'basic_salary' => $this->basic_salary,
                    'allowances' => $this->allowances,
                    'bonus' => $this->bonus ?: 0,
                    'overtime' => $this->overtime ?: 0,
                    'deductions' => $this->deductions ?: 0,
                    'tax' => $this->tax ?: 0,
                    'net_salary' => $this->net_salary,
                    'status' => $this->status,
                    'paid_date' => $this->status === 'paid' ? $this->paid_date : null,
                ]);
            }
        } else {
            $payroll = Payroll::create([
                'employee_id' => $this->employee_id,
                'month' => $this->month,
                'basic_salary' => $this->basic_salary,
                'allowances' => $this->allowances,
                'bonus' => $this->bonus ?: 0,
                'overtime' => $this->overtime ?: 0,
                'deductions' => $this->deductions ?: 0,
                'tax' => $this->tax ?: 0,
                'net_salary' => $this->net_salary,
                'status' => $this->status,
                'paid_date' => $this->status === 'paid' ? $this->paid_date : null,
            ]);
        }

        SalaryPayment::updateOrCreate(
            ['id' => $this->editId],
            [
                'employee_id' => $this->employee_id,
                'month' => $this->month,
                'salary_id' => $salary->id,
                'payroll_id' => $payroll->id,
                'amount' => $this->net_salary,
                'payment_method' => $this->payment_method,
                'status' => $this->status,
                'paid_date' => $this->status === 'paid' ? $this->paid_date : null,
            ],
        );

        if (!$this->editId && $this->status === 'paid') {
            $category = ExpenseCategory::firstOrCreate([
                'name' => 'salary',
            ]);

            Expense::create([
                'expense_category_id' => $category->id,
                'payment_method' => $this->payment_method,
                'amount' => $this->net_salary,
                'expense_date' => $this->paid_date,
                'status' => 'completed',
            ]);
        }

        session()->flash('success', $this->editId ? 'Salary updated successfully.' : 'Salary payment created successfully.');

        $this->resetForm();
        $this->loadPayments();
    }

    public function edit($id)
    {
        $payment = SalaryPayment::with('payroll')->findOrFail($id);

        $this->editId = $payment->id;
        $this->employee_id = $payment->employee_id;
        $this->month = $payment->month;

        $this->basic_salary = $payment->payroll->basic_salary ?? 0;
        $this->allowances = $payment->payroll->allowances ?? 0;
        $this->bonus = $payment->payroll->bonus ?? 0;
        $this->overtime = $payment->payroll->overtime ?? 0;
        $this->deductions = $payment->payroll->deductions ?? 0;
        $this->tax = $payment->payroll->tax ?? 0;
        $this->net_salary = $payment->payroll->net_salary ?? $payment->amount;

        $this->payment_method = $payment->payment_method;
        $this->status = $payment->status;
        $this->paid_date = $payment->paid_date ?? now()->format('Y-m-d');
    }

    public function delete($id)
    {
        SalaryPayment::findOrFail($id)->delete();

        session()->flash('success', 'Salary payment deleted successfully.');

        $this->loadPayments();
    }

    public function resetForm()
    {
        $this->editId = null;
        $this->employee_id = '';
        $this->month = now()->format('Y-m');
        $this->basic_salary = 0;
        $this->allowances = 0;
        $this->bonus = 0;
        $this->overtime = 0;
        $this->deductions = 0;
        $this->tax = 0;
        $this->net_salary = 0;
        $this->payment_method = 'cash';
        $this->status = 'pending';
        $this->paid_date = now()->format('Y-m-d');
    }

    public function loadPayments()
    {
        $this->salaryPayments = SalaryPayment::with(['employee.user', 'payroll'])
            ->latest()
            ->get();
    }
};
?>

<div class="container py-4">

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">{{ $editId ? 'Edit Salary Payment' : 'Create Salary Payment' }}</h5>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form wire:submit.prevent="save">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Employee</label>
                        <select wire:model.live="employee_id" class="form-select">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->user->name ?? ($employee->name ?? 'Employee') }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Month</label>
                        <input type="month" wire:model="month" class="form-control">
                        @error('month')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payment Method</label>
                        <select wire:model="payment_method" class="form-select">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                            <option value="jazzcash">JazzCash</option>
                            <option value="easypaisa">Easypaisa</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Basic Salary</label>
                        <input type="number" wire:model.live="basic_salary" class="form-control" readonly>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Allowances</label>
                        <input type="number" wire:model.live="allowances" class="form-control" readonly>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Bonus</label>
                        <input type="number" wire:model.live="bonus" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Overtime</label>
                        <input type="number" wire:model.live="overtime" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Deductions</label>
                        <input type="number" wire:model.live="deductions" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tax</label>
                        <input type="number" wire:model.live="tax" class="form-control" readonly>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>
                        <select wire:model.live="status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>

                    @if ($status === 'paid')
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Paid Date</label>
                            <input type="date" wire:model="paid_date" class="form-control">
                        </div>
                    @endif

                    <div class="col-md-12 mb-3">
                        <div class="alert alert-info">
                            <strong>Net Salary:</strong>
                            Rs {{ number_format((float) $net_salary, 2) }}
                        </div>
                    </div>

                </div>

                <button class="btn btn-primary">
                    {{ $editId ? 'Update Salary' : 'Pay Salary' }}
                </button>

                @if ($editId)
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">
                        Cancel
                    </button>
                @endif
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Salary Payment History</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Month</th>
                        <th>Basic</th>
                        <th>Allowance</th>
                        <th>Bonus</th>
                        <th>Overtime</th>
                        <th>Deductions</th>
                        <th>Tax</th>
                        <th>Net Salary</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Paid Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($salaryPayments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $payment->employee->user->name ?? ($payment->employee->name ?? 'N/A') }}</td>
                            <td>{{ $payment->month }}</td>
                            <td>Rs {{ number_format($payment->payroll->basic_salary ?? 0, 2) }}</td>
                            <td>Rs {{ number_format($payment->payroll->allowances ?? 0, 2) }}</td>
                            <td>Rs {{ number_format($payment->payroll->bonus ?? 0, 2) }}</td>
                            <td>Rs {{ number_format($payment->payroll->overtime ?? 0, 2) }}</td>
                            <td>Rs {{ number_format($payment->payroll->deductions ?? 0, 2) }}</td>
                            <td>Rs {{ number_format($payment->payroll->tax ?? 0, 2) }}</td>
                            <td>
                                <strong>Rs
                                    {{ number_format($payment->payroll->net_salary ?? $payment->amount, 2) }}</strong>
                            </td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>
                                @if ($payment->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>{{ $payment->paid_date ?? '-' }}</td>
                            <td>
                                <button wire:click="edit({{ $payment->id }})" class="btn btn-sm btn-info">
                                    Edit
                                </button>

                                <button wire:click="delete({{ $payment->id }})"
                                    onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center text-muted">
                                No salary payments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>
