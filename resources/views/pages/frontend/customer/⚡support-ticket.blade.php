<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public string $order_id = '';
    public string $subject = '';
    public string $priority = 'medium';
    public string $message = '';

    public array $orders = [['id' => 1001, 'label' => '#1001 - Rs 185,000 - Delivered'], ['id' => 1002, 'label' => '#1002 - Rs 45,000 - Processing'], ['id' => 1003, 'label' => '#1003 - Rs 78,000 - Shipped']];

    public function submitTicket(): void
    {
        $this->validate([
            'order_id' => 'required',
            'subject' => 'required|min:3',
            'priority' => 'required',
            'message' => 'required|min:10',
        ]);

        session()->flash('success', 'Support ticket submitted successfully.');

        $this->reset(['order_id', 'subject', 'message']);
        $this->priority = 'medium';
    }
};
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">Create Support Ticket</h2>

    <div class="row g-4">
        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success rounded-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="submitTicket">

                        <div class="mb-3">
                            <label class="form-label">Select Order Number</label>
                            <select wire:model="order_id" class="form-select rounded-pill">
                                <option value="">Choose order</option>

                                @foreach ($orders as $order)
                                    <option value="{{ $order['id'] }}">
                                        {{ $order['label'] }}
                                    </option>
                                @endforeach
                            </select>

                            @error('order_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" wire:model="subject" class="form-control rounded-pill"
                                placeholder="Example: Product not delivered">

                            @error('subject')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select wire:model="priority" class="form-select rounded-pill">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>

                            @error('priority')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea wire:model="message" rows="6" class="form-control rounded-4" placeholder="Explain your issue"></textarea>

                            @error('message')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button class="btn btn-primary rounded-pill px-4">
                            Submit Ticket
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
