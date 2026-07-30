<?php

use Livewire\Component;
use App\Models\ContactUs;

new class extends Component {
    public string $search = '';

    public array $categories = [];

    public function mount(): void
    {
        $this->loadContactUsMessages();
    }

    public function updatedSearch(): void
    {
        $this->loadContactUsMessages();
    }

    public function loadContactUsMessages(): void
    {
        $this->categories = ContactUs::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->get()
            ->toArray();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Contact Us Messages</h3>
            <p class="text-muted mb-0">Manage contact us message</p>
        </div>


    </div>

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search ...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>

                        <th>Subject</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>#{{ $category['id'] }}</td>

                            <td>{{ $category['name'] }}</td>


                            <td>{{ $category['subject'] }}</td>

                            <td>
                                @if ($category['status'] != 'replied')
                                    <a href="{{ route('contact-us.reply', $category['id']) }}"
                                        class="btn btn-sm btn-info rounded-pill text-white">
                                        Reply
                                    </a>
                                @else
                                    <span class="btn btn-sm rounded-pill text-white btn-success">Replied</span>
                                @endif
                                <a href="{{ route('contact-us.show', $category['id']) }}"
                                    class="btn btn-sm btn-info rounded-pill text-white">
                                    View
                                </a>

                                <button class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No Contact Us Message found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
