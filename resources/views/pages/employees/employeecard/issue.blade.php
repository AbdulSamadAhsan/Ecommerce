<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\EmployeeDocument;
use App\Models\Employee;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;
new class extends Component {
    use WithFileUploads;
    public $employee_id = '';

    public $document_type = '';

    public $title = '';

    public $document_number = '';

    public $file;

    public $expiry_date = '';

    public $remarks = '';
    public $issue_date = '';
    public $employees = [];
    protected function generateDocumentNumber(): string
    {
        $prefix = match ($this->document_type) {
            'CNIC' => 'CNIC',

            'Passport' => 'PASS',

            'DrivingLicense' => 'DL',

            'EmployeeCard' => 'EC',

            default => strtoupper(Str::substr($this->document_type, 0, 3)),
        };

        $last = EmployeeDocument::where('document_type', $this->document_type)->latest('id')->first();
        if ($last) {
            $string = $last->document_number;
            $numberdoc = (int) substr($string, -2);
        } else {
            $numberdoc = 0;
        }
        $number = $last ? $numberdoc + 1 : 1;

        return $prefix . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
    public function mount()
    {
        $employeeIds = EmployeeDocument::where('document_type', 'EmployeeCard')->pluck('employee_id');

        $this->employees = Employee::with('user')->whereNotIn('id', $employeeIds)->get()->sortBy('user.name');

        // or
        $this->expiry_date = Carbon::now()->addYears(5)->toDateString();
        $this->issue_date = now()->toDateString();
        $this->document_type = 'EmployeeCard';
        $this->document_number = $this->generateDocumentNumber();
    }
    protected function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',

            'document_number' => 'nullable|max:255',

            'expiry_date' => [
                'nullable',
                'date',
                'after:issue_date',
                function ($attribute, $value, $fail) {
                    if ($this->issue_date) {
                        $expected = \Carbon\Carbon::parse($this->issue_date)->addYears(5)->toDateString();

                        if ($value !== $expected) {
                            $fail('For a  the expiry date must be exactly 5 years after the issue date.');
                        }
                    }
                },
            ],

            'remarks' => 'nullable|max:1000',
        ];
    }

    public function save()
    {
        $this->validate();
        $expire_date = $this->expiry_date;
        $created_date = $this->issue_date;
        $employee = Employee::with('user')->find($this->employee_id);
        $exists = EmployeeDocument::where('document_type', 'EmployeeCard')->where('employee_id', $this->employee_id)->exists();
        if ($exists) {
            session()->flash('error', $employee->user->name . 'Already Have Employee Card');
            return;
        }
        //dd('Issuing Employee', $this->issue_date, $this->expiry_date, $this->document_type, $this->document_number);

        $html = view('employees.card', ['employee' => $employee, 'expiry_date' => $this->expiry_date, 'issue_date' => $this->issue_date])->render();
        Storage::disk('public')->makeDirectory('cards');
        $relativePath = "cards/employee-{$employee->user->name}.png";

        Browsershot::html($html)
            ->windowSize(1000, 1000)
            ->deviceScaleFactor(2)
            ->save(Storage::disk('public')->path($relativePath));

        EmployeeDocument::create([
            'employee_id' => $this->employee_id,
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
            'file' => $relativePath,
            'issue_date' => $this->issue_date,
            'expiry_date' => $this->expiry_date,
        ]);
        session()->flash('success', 'Employee document uploaded successfully.');
        return redirect()->route('employees.employee_card');
    }
    //
};
?>
<div class="row">
    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Issue Employee Card
                </h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-success">
                        {{ session('error') }}
                    </div>
                @endif
                <form wire:submit="save">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Employee
                            </label>

                            <select class="form-select" wire:model="employee_id">

                                <option value="">
                                    Select Employee
                                </option>

                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">

                                        {{ $employee->user->name }}

                                    </option>
                                @endforeach

                            </select>

                            @error('employee_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>



                    </div>

                    <div class="row">




                    </div>



                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Issue Date
                            </label>

                            <input type="date" class="form-control" wire:model="issue_date">


                            @error('issue_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Expiry Date
                            </label>

                            <input type="date" class="form-control" wire:model="expiry_date">


                            @error('expiry_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>



                    <div class="d-flex justify-content-end">

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">

                            <span wire:loading.remove>
                                Issue Employee Card
                            </span>

                            <span wire:loading>
                                Issusing Employee Card
                            </span>

                        </button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
