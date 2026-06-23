<?php

use Livewire\Component;

new class extends Component {
    public $search = '';
    public $department = '';
    public $status = '';

    public $selectedEmployeeId = '';
    public $payment_date = '';
    public $payment_method = 'cash';
    public $bonus = 0;
    public $deduction = 0;
    public $remarks = '';

    public $salaryPayments = [];

    public $employees = [
        [
            'id' => 1,
            'name' => 'Ali Khan',
            'department' => 'Sales',
            'designation' => 'Sales Officer',
            'salary' => 45000,
            'status' => 'unpaid',
        ],
        [
            'id' => 2,
            'name' => 'Sara Ahmed',
            'department' => 'Accounts',
            'designation' => 'Accountant',
            'salary' => 60000,
            'status' => 'paid',
        ],
        [
            'id' => 3,
            'name' => 'Usman Raza',
            'department' => 'Warehouse',
            'designation' => 'Inventory Manager',
            'salary' => 55000,
            'status' => 'unpaid',
        ],
    ];

    public function mount()
    {
        $this->payment_date = now()->format('Y-m-d');
    }

    public function getFilteredEmployeesProperty()
    {
        return collect($this->employees)
            ->when($this->search, function ($items) {
                return $items->filter(function ($employee) {
                    return str_contains(strtolower($employee['name']), strtolower($this->search)) || str_contains(strtolower($employee['designation']), strtolower($this->search));
                });
            })
            ->when($this->department, fn($items) => $items->where('department', $this->department))
            ->when($this->status, fn($items) => $items->where('status', $this->status))
            ->values();
    }

    public function getDepartmentsProperty()
    {
        return collect($this->employees)->pluck('department')->unique()->values();
    }

    public function selectEmployee($id)
    {
        $employee = collect($this->employees)->firstWhere('id', (int) $id);

        if (!$employee) {
            return;
        }

        $this->selectedEmployeeId = $employee['id'];
        $this->bonus = 0;
        $this->deduction = 0;
        $this->remarks = '';
    }

    public function getSelectedEmployeeProperty()
    {
        return collect($this->employees)->firstWhere('id', (int) $this->selectedEmployeeId);
    }

    public function getNetSalaryProperty()
    {
        if (!$this->selectedEmployee) {
            return 0;
        }

        return max((float) $this->selectedEmployee['salary'] + (float) $this->bonus - (float) $this->deduction, 0);
    }

    public function paySalary()
    {
        if (!$this->selectedEmployee) {
            session()->flash('error', 'Please select an employee.');
            return;
        }

        $this->salaryPayments[] = [
            'reference_no' => 'SAL-' . now()->format('YmdHis'),
            'employee_id' => $this->selectedEmployee['id'],
            'employee_name' => $this->selectedEmployee['name'],
            'department' => $this->selectedEmployee['department'],
            'basic_salary' => $this->selectedEmployee['salary'],
            'bonus' => (float) $this->bonus,
            'deduction' => (float) $this->deduction,
            'net_salary' => $this->netSalary,
            'payment_method' => $this->payment_method,
            'payment_date' => $this->payment_date,
            'remarks' => $this->remarks,
            'status' => 'paid',
        ];

        foreach ($this->employees as $index => $employee) {
            if ($employee['id'] == $this->selectedEmployeeId) {
                $this->employees[$index]['status'] = 'paid';
            }
        }

        session()->flash('success', 'Salary paid successfully.');

        $this->resetPaymentForm();
    }

    public function resetPaymentForm()
    {
        $this->selectedEmployeeId = '';
        $this->payment_date = now()->format('Y-m-d');
        $this->payment_method = 'cash';
        $this->bonus = 0;
        $this->deduction = 0;
        $this->remarks = '';
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->department = '';
        $this->status = '';
    }
};
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Salary Payments</h4>
            <p class="text-muted mb-0">Dynamic salary payment demo without database</p>
        </div>

        <button type="button" wire:click="resetPaymentForm" class="btn btn-secondary rounded-pill">
            Reset Form
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger rounded-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">

        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="row align-items-center g-2">
                        <div class="col-md-4">
                            <h5 class="mb-0 fw-bold">Employees</h5>
                        </div>

                        <div class="col-md-3">
                            <input type="text" wire:model.live="search" class="form-control rounded-pill"
                                placeholder="Search employee">
                        </div>

                        <div class="col-md-2">
                            <select wire:model.live="department" class="form-control rounded-pill">
                                <option value="">Department</option>
                                @foreach ($this->departments as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select wire:model.live="status" class="form-control rounded-pill">
                                <option value="">Status</option>
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <button type="button" wire:click="resetFilters"
                                class="btn btn-secondary rounded-pill w-100">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Salary</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($this->filteredEmployees as $employee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $employee['name'] }}</strong></td>
                                    <td>{{ $employee['department'] }}</td>
                                    <td>{{ $employee['designation'] }}</td>
                                    <td>Rs. {{ number_format($employee['salary'], 2) }}</td>
                                    <td>
                                        @if ($employee['status'] === 'paid')
                                            <span class="badge bg-success rounded-pill">Paid</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button type="button" wire:click="selectEmployee({{ $employee['id'] }})"
                                            class="btn btn-sm btn-primary rounded-pill">
                                            Pay
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No employees found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Payment Form</h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label">Selected Employee</label>
                        <input type="text" class="form-control rounded-pill"
                            value="{{ $this->selectedEmployee['name'] ?? 'No employee selected' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Basic Salary</label>
                        <input type="text" class="form-control rounded-pill"
                            value="Rs. {{ number_format($this->selectedEmployee['salary'] ?? 0, 2) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bonus</label>
                        <input type="number" wire:model.live="bonus" class="form-control rounded-pill">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deduction</label>
                        <input type="number" wire:model.live="deduction" class="form-control rounded-pill">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Net Salary</label>
                        <input type="text" class="form-control rounded-pill"
                            value="Rs. {{ number_format($this->netSalary, 2) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" wire:model="payment_date" class="form-control rounded-pill">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select wire:model="payment_method" class="form-control rounded-pill">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                            <option value="card">Card</option>
                            <option value="wallet">Wallet</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea wire:model="remarks" class="form-control rounded-4"></textarea>
                    </div>

                    <button type="button" wire:click="paySalary" class="btn btn-success rounded-pill w-100">
                        Pay Salary
                    </button>

                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Payment History</h5>
        </div>

        <div class="card-body table-responsive pt-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Basic</th>
                        <th>Bonus</th>
                        <th>Deduction</th>
                        <th>Net</th>
                        <th>Method</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($salaryPayments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $payment['reference_no'] }}</strong></td>
                            <td>{{ $payment['employee_name'] }}</td>
                            <td>{{ $payment['department'] }}</td>
                            <td>Rs. {{ number_format($payment['basic_salary'], 2) }}</td>
                            <td>Rs. {{ number_format($payment['bonus'], 2) }}</td>
                            <td>Rs. {{ number_format($payment['deduction'], 2) }}</td>
                            <td><strong>Rs. {{ number_format($payment['net_salary'], 2) }}</strong></td>
                            <td>{{ ucfirst($payment['payment_method']) }}</td>
                            <td>{{ $payment['payment_date'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                No payment history found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
