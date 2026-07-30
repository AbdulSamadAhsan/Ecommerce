<?php

use Livewire\Component;
use App\Models\EmployeeDocument;

new class extends Component {
    public string $search = '';

    public array $documents = [];

    public function mount(): void
    {
        $this->loadDocuments();
    }

    public function updatedSearch(): void
    {
        $this->loadDocuments();
    }

    public function loadDocuments(): void
    {
        $this->documents = EmployeeDocument::with('employee')
            ->when($this->search, function ($query) {
                $query

                    ->where('document_type', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%')
                    ->orWhere('document_number', 'like', '%' . $this->search . '%');
            })
            ->where('document_type', 'EmployeeCard')
            ->latest()
            ->get()
            ->toArray();
    }
};

?>
<div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Employee Card
            </h3>

            <p class="text-muted mb-0">
                Manage employee card
            </p>
            <a href="{{ route('employees.employee_card.create') }}" class="btn btn-primary rounded-pill">

                Generate Employee Card

            </a>
        </div>


    </div>

    <div class="dashboard-card">

        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search employee, title, document number...">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead class="table-light">

                    <tr>

                        <th>ID</th>

                        <th>Employee</th>



                        <th>Title</th>

                        <th>Number</th>

                        <th>Issue Date</th>

                        <th>Expiry Date</th>

                        <th>File</th>



                    </tr>

                </thead>

                <tbody>

                    @forelse($documents as $document)
                        <tr>

                            <td>

                                #{{ $document['id'] }}

                            </td>

                            <td>

                                {{ $document['employee']['user']['name'] }}

                            </td>

                            <td>

                                <span class="badge bg-info">

                                    {{ Str::headline($document['document_type']) }}

                                </span>

                            </td>


                            <td>

                                {{ $document['document_number'] ?: '-' }}

                            </td>

                            <td>

                                {{ $document['issue_date'] ?: '-' }}

                            </td>

                            <td>

                                {{ $document['expiry_date'] ?: '-' }}

                            </td>

                            <td>

                                @if ($document['file'])
                                    <a href="{{ Storage::url($document['file']) }}" target="_blank" download
                                        class="btn btn-sm btn-secondary">

                                        View File

                                    </a>
                                @else
                                    -
                                @endif

                            </td>



                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center text-muted py-4">

                                No employee documents found.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
