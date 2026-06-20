<?php

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return;
        }

        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        session()->flash('success', 'Brand deleted successfully.');
    }

    public function with(): array
    {
        return [
            'brands' => Brand::query()->withCount('products')->when($this->search, fn($query) => $query->where('title', 'like', '%' . $this->search . '%'))->latest()->paginate(10),
        ];
    }
};

?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Brand List
            </h3>

            <p class="text-muted mb-0">
                Manage all brands
            </p>
        </div>



    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>
    @endif

    <div class="dashboard-card">

        <div class="row mb-4">

            <div class="col-md-6">

                <input type="text" wire:model.live.debounce.300ms="search" class="form-control rounded-4"
                    placeholder="Search Brand...">

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th>ID</th>
                        <th>Logo</th>
                        <th>Brand Name</th>
                        <th>No Of Products</th>
                        <th>Status</th>
                        <th width="180">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($brands as $brand)
                        <tr>

                            <td>#{{ $brand->id }}</td>

                            <td>

                                @if ($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" width="50" height="50"
                                        class="rounded-3 border" style="object-fit:cover;">
                                @else
                                    <div class="border rounded-3 d-flex align-items-center justify-content-center bg-light"
                                        style="width:50px;height:50px;">

                                        <i class="bi bi-image"></i>

                                    </div>
                                @endif

                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $brand->title }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">
                                    {{ $brand->products_count }}
                                </div>
                            </td>



                            <td>

                                @if ($brand->status)
                                    <span class="badge bg-success rounded-pill">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-danger rounded-pill">
                                        Inactive
                                    </span>
                                @endif

                            </td>

                            <td>

                                <div class="d-flex gap-2">

                                    <a href="{{ route('brands.edit', $brand->id) }}" wire:navigate
                                        class="btn btn-sm btn-primary rounded-pill">

                                        Edit

                                    </a>

                                    <a href="{{ route('brands.show', $brand->id) }}" wire:navigate
                                        class="btn btn-sm btn-secondary rounded-pill">

                                        View

                                    </a>

                                    <button wire:click="delete({{ $brand->id }})"
                                        wire:confirm="Are you sure you want to delete this brand?"
                                        class="btn btn-sm btn-danger rounded-pill">

                                        Delete

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="text-center py-5">
                                No brands found
                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $brands->links() }}

        </div>

    </div>

</div>
