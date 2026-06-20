<?php

use Livewire\Component;
use App\Models\Category;

new class extends Component {
    public string $search = '';

    public array $categories = [];

    public function mount(): void
    {
        $this->loadCategories();
    }

    public function updatedSearch(): void
    {
        $this->loadCategories();
    }

    public function loadCategories(): void
    {
        $this->categories = Category::withCount('products')
            ->when($this->search, function ($query) {
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
            <h3 class="fw-bold mb-1">Categories</h3>
            <p class="text-muted mb-0">Manage product categories</p>
        </div>

        <a href="{{ route('categories.create') }}" class="btn btn-primary rounded-pill">
            Add Category
        </a>
    </div>

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search category...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>#{{ $category['id'] }}</td>

                            <td>{{ $category['name'] }}</td>

                            <td>{{ $category['products_count'] }}</td>

                            <td>
                                <span class="badge {{ $category['status'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $category['status'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('categories.show', $category['id']) }}"
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
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
