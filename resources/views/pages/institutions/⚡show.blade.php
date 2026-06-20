<?php

use Livewire\Component;
use App\Models\Institution;

new class extends Component {
    public int $id;

    public $institution;

    public $educations;

    public function mount($id): void
    {
        $this->institution = Institution::findOrFail($id);
    }
};
?>
<div>

    <div class="d-flex justify-content-between mb-4">

        <div>
            <h3 class="fw-bold">
                Institution Details
            </h3>
        </div>

        <a href="{{ route('institutions.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>

    </div>

    <div class="card shadow border-0 mb-4">

        <div class="card-body">

            <h4>
                {{ $institution->name }}
            </h4>

            <hr>

            <p>
                <strong>Type:</strong>
                {{ ucfirst($institution->type) }}
            </p>

            <p>
                <strong>City:</strong>
                {{ $institution->city }}
            </p>

            <p>
                <strong>Address:</strong>
                {{ $institution->address }}
            </p>



        </div>

    </div>



</div>
