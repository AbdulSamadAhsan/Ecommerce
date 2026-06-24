<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use App\Models\Payroll;

new class extends Component {
    use WithPagination;

    public $payroll_id = null;

    public $employee_id = '';
    public $payroll_no = '';
    public $month = '';
    public $basic_salary = 0;
    public $allowances = 0;
    public $bonus = 0;
    public $overtime = 0;
    public $deductions = 0;
    public $tax = 0;
    public $net_salary = 0;
    public $paid_date = '';
    public $payment_method = 'cash';
    public $status = 'pending';
    public $remarks = '';

    public $search = '';
    public $filter_status = '';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->month = now()->format('Y-m');
        $this->paid_date = now()->format('Y-m-d');
        $this->payroll_no = $this->generatePayrollNo();
    }

    public function generatePayrollNo()
    {
        $last = Payroll::latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;

        return 'PAY-' . now()->format('Ymd') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    public function updatedEmployeeId()
    {
        $employee = Employee::find($this->employee_id);

        $this->basic_salary = $employee?->salary ?? 0;

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
        $this->net_salary = ((float) $this->basic_salary) + ((float) $this->allowances) + ((float) $this->bonus) + ((float) $this->overtime) - ((float) $this->deductions) - ((float) $this->tax);

        $this->net_salary = max($this->net_salary, 0);
    }

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'payroll_no' => 'required|string|max:255',
            'month' => 'required|string|max:20',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'paid_date' => 'nullable|date',
            'payment_method' => 'required|in:cash,bank,card,wallet,cheque',
            'status' => 'required|in:pending,paid,cancelled',
            'remarks' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->calculateNetSalary();
        $this->validate();

        Payroll::updateOrCreate(
            [
                'employee_id' => $this->employee_id,
                'month' => $this->month,
            ],
            [
                'payroll_no' => $this->payroll_no,
                'basic_salary' => $this->basic_salary,
                'allowances' => $this->allowances ?? 0,
                'bonus' => $this->bonus ?? 0,
                'overtime' => $this->overtime ?? 0,
                'deductions' => $this->deductions ?? 0,
                'tax' => $this->tax ?? 0,
                'net_salary' => $this->net_salary,
                'paid_date' => $this->paid_date ?: null,
                'payment_method' => $this->payment_method,
                'status' => $this->status,
                'remarks' => $this->remarks,
            ],
        );

        session()->flash('success', 'Payroll saved successfully.');

        $this->resetForm();
    }

    public function edit($id)
    {
        $payroll = Payroll::findOrFail($id);

        $this->payroll_id = $payroll->id;
        $this->employee_id = $payroll->employee_id;
        $this->payroll_no = $payroll->payroll_no;
        $this->month = $payroll->month;
        $this->basic_salary = $payroll->basic_salary;
        $this->allowances = $payroll->allowances;
        $this->bonus = $payroll->bonus;
        $this->overtime = $payroll->overtime;
        $this->deductions = $payroll->deductions;
        $this->tax = $payroll->tax;
        $this->net_salary = $payroll->net_salary;
        $this->paid_date = $payroll->paid_date?->format('Y-m-d');
        $this->payment_method = $payroll->payment_method;
        $this->status = $payroll->status;
        $this->remarks = $payroll->remarks;
    }

    public function delete($id)
    {
        Payroll::findOrFail($id)->delete();

        session()->flash('success', 'Payroll deleted successfully.');
    }

    public function resetForm()
    {
        $this->payroll_id = null;
        $this->employee_id = '';
        $this->payroll_no = $this->generatePayrollNo();
        $this->month = now()->format('Y-m');
        $this->basic_salary = 0;
        $this->allowances = 0;
        $this->bonus = 0;
        $this->overtime = 0;
        $this->deductions = 0;
        $this->tax = 0;
        $this->net_salary = 0;
        $this->paid_date = now()->format('Y-m-d');
        $this->payment_method = 'cash';
        $this->status = 'pending';
        $this->remarks = '';

        $this->resetValidation();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filter_status = '';
        $this->resetPage();
    }

    public function with()
    {
        return [
            'employees' => Employee::with('user')->latest()->get(),

            'payrolls' => Payroll::with('employee.user')
                ->when($this->search, function ($query) {
                    $query
                        ->where('payroll_no', 'like', '%' . $this->search . '%')
                        ->orWhere('month', 'like', '%' . $this->search . '%')
                        ->orWhereHas('employee.user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('employee', function ($q) {
                            $q->where('phone', 'like', '%' . $this->search . '%')->orWhere('designation', 'like', '%' . $this->search . '%');
                        });
                })
                ->when($this->filter_status, function ($query) {
                    $query->where('status', $this->filter_status);
                })
                ->latest()
                ->paginate(10),
        ];
    }
};
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Employee Payroll</h4>
            <p class="text-muted mb-0">Manage employee payroll and salary records</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    {{ $payroll_id ? 'Update Payroll' : 'Create Payroll' }}
                </h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Employee</label>
                        <select wire:model.live="employee_id" class="form-control rounded-pill">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->user->name ?? ($employee->name ?? 'N/A') }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payroll No</label>
                        <input type="text" wire:model="payroll_no" class="form-control rounded-pill">
                        @error('payroll_no')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Month</label>
                        <input type="month" wire:model="month" class="form-control rounded-pill">
                        @error('month')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Basic Salary</label>
                        <input type="number" wire:model.live="basic_salary" class="form-control rounded-pill">
                        @error('basic_salary')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Allowances</label>
                        <input type="number" wire:model.live="allowances" class="form-control rounded-pill">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Bonus</label>
                        <input type="number" wire:model.live="bonus" class="form-control rounded-pill">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Overtime</label>
                        <input type="number" wire:model.live="overtime" class="form-control rounded-pill">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Deductions</label>
                        <input type="number" wire:model.live="deductions" class="form-control rounded-pill">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tax</label>
                        <input type="number" wire:model.live="tax" class="form-control rounded-pill">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Net Salary</label>
                        <input type="number" value="{{ $net_salary }}" class="form-control rounded-pill" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Paid Date</label>
                        <input type="date" wire:model="paid_date" class="form-control rounded-pill">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payment Method</label>
                        <select wire:model="payment_method" class="form-control rounded-pill">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                            <option value="card">Card</option>
                            <option value="wallet">Wallet</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select wire:model="status" class="form-control rounded-pill">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea wire:model="remarks" class="form-control rounded-4"></textarea>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary rounded-pill">
                    {{ $payroll_id ? 'Update Payroll' : 'Save Payroll' }}
                </button>

                <button type="button" wire:click="resetForm" class="btn btn-warning rounded-pill">
                    Reset
                </button>

            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center g-2">
                <div class="col-md-5">
                    <h5 class="mb-0 fw-bold">Payroll List</h5>
                </div>

                <div class="col-md-4">
                    <input type="text" wire:model.live="search" class="form-control rounded-pill"
                        placeholder="Search payroll">
                </div>

                <div class="col-md-2">
                    <select wire:model.live="filter_status" class="form-control rounded-pill">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button wire:click="resetFilters" class="btn btn-secondary rounded-pill w-100">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body table-responsive pt-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Payroll No</th>
                        <th>Employee</th>
                        <th>Month</th>
                        <th>Basic</th>
                        <th>Allowance</th>
                        <th>Bonus</th>
                        <th>Deduction</th>
                        <th>Net</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($payrolls as $payroll)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $payroll->payroll_no }}</strong></td>
                            <td>{{ $payroll->employee->user->name ?? ($payroll->employee->name ?? 'N/A') }}</td>
                            <td>{{ $payroll->month }}</td>
                            <td>Rs. {{ number_format($payroll->basic_salary, 2) }}</td>
                            <td>Rs. {{ number_format($payroll->allowances, 2) }}</td>
                            <td>Rs. {{ number_format($payroll->bonus, 2) }}</td>
                            <td>Rs. {{ number_format($payroll->deductions, 2) }}</td>
                            <td><strong>Rs. {{ number_format($payroll->net_salary, 2) }}</strong></td>
                            <td>
                                @if ($payroll->status === 'paid')
                                    <span class="badge bg-success rounded-pill">Paid</span>
                                @elseif ($payroll->status === 'cancelled')
                                    <span class="badge bg-danger rounded-pill">Cancelled</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('payrolls.show', $payroll->id) }}"
                                    class="btn btn-sm btn-secondary rounded-pill">
                                    View
                                </a>

                                <button wire:click="edit({{ $payroll->id }})"
                                    class="btn btn-sm btn-info rounded-pill">
                                    Edit
                                </button>

                                <button wire:click="delete({{ $payroll->id }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                No payroll found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>

</div>
