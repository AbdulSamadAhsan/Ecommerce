<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\EmployeeDocument;
use App\Models\Employee;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
new class extends Component {
    use WithFileUploads;
    public $employee_id = '';

    public $document_type = '';

    public $title = '';

    public $document_number = '';

    public $file;

    public $issue_date = '';

    public $expiry_date = '';

    public $remarks = '';

    public $employees = [];
    public function mount()
    {
        $employeeIds = EmployeeDocument::where('document_type', '!=', 'EmployeeCard')->pluck('employee_id');
        $this->employees = Employee::with('user')->whereNotIn('id', $employeeIds)->get()->sortBy('user.name');
    }
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
    protected function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',

            'document_type' => [
                'required',
                'max:100',
                'in:CNIC,resume',
                Rule::unique('employee_documents')->where(function ($query) {
                    return $query->where('employee_id', $this->employee_id);
                }),
            ],

            'document_number' => 'nullable|max:255',

            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',

            'issue_date' => [Rule::requiredIf(strtolower($this->document_type) === 'cnic'), 'nullable', 'date'],

            'expiry_date' => [
                Rule::requiredIf(strtolower($this->document_type) === 'cnic'),
                'nullable',
                'date',
                'after:issue_date',
                function ($attribute, $value, $fail) {
                    if (strtolower($this->document_type) === 'cnic' && $this->issue_date) {
                        $expected = \Carbon\Carbon::parse($this->issue_date)->addYears(5)->toDateString();

                        if ($value !== $expected) {
                            $fail('For a CNIC, the expiry date must be exactly 5 years after the issue date.');
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
        if (strtolower($this->document_type) === 'cnic') {
            $issueDate = Carbon::parse($this->issue_date);
            $expiryDate = Carbon::parse($this->expiry_date);

            if (!$expiryDate->equalTo($issueDate->copy()->addYears(5))) {
                throw ValidationException::withMessages([
                    'expiry_date' => 'For a CNIC, the expiry date must be exactly 5 years after the issue date.',
                ]);
            }
        }

        $path = $this->file->store('employee-documents', 'public');

        EmployeeDocument::create([
            'employee_id' => $this->employee_id,

            'document_type' => $this->document_type,

            'document_number' => $this->generateDocumentNumber(),

            'file' => $path,

            'issue_date' => $this->issue_date,

            'expiry_date' => $this->expiry_date,

            'remarks' => $this->remarks,
        ]);

        $this->reset(['employee_id', 'document_type', 'title', 'document_number', 'file', 'issue_date', 'expiry_date', 'remarks']);

        session()->flash('success', 'Employee document uploaded successfully.');
    }
    //
};
?>
<div class="row">
    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Upload Document
                </h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
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

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Document Type
                            </label>

                            <input type="text" class="form-control" placeholder="Passport, CNIC, Driving License..."
                                wire:model.live="document_type">

                            @error('document_type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Title
                            </label>

                            <input type="text" class="form-control" wire:model.live="title">

                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Document Number
                            </label>

                            <input type="text" class="form-control" wire:model.live="document_number">

                            @error('document_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Upload File
                        </label>

                        <input type="file" class="form-control" wire:model="file">

                        <div wire:loading wire:target="file" class="text-primary mt-2">
                            Uploading...
                        </div>

                        @error('file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

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

                    <div class="mb-4">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea rows="4" class="form-control" wire:model.live="remarks"></textarea>

                    </div>

                    <div class="d-flex justify-content-end">

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">

                            <span wire:loading.remove>
                                Upload Document
                            </span>

                            <span wire:loading>
                                Uploading...
                            </span>

                        </button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
