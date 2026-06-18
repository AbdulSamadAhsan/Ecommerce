<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $subject = '';
    public string $message = '';

    public function sendMessage(): void
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'nullable|min:11',
            'subject' => 'required|min:3',
            'message' => 'required|min:10',
        ]);

        session()->flash('success', 'Thank you! Your message has been submitted successfully.');

        $this->reset();
    }
};
?>

<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold">Contact Us</h1>
        <p class="text-muted">
            We would love to hear from you.
        </p>
    </div>

    <div class="row g-4">

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">

                    <h4 class="fw-bold mb-4">
                        Contact Information
                    </h4>

                    <div class="mb-4">
                        <i class="bi bi-geo-alt-fill text-primary"></i>
                        Karachi, Sindh, Pakistan
                    </div>

                    <div class="mb-4">
                        <i class="bi bi-telephone-fill text-success"></i>
                        +92 300 1234567
                    </div>

                    <div class="mb-4">
                        <i class="bi bi-envelope-fill text-danger"></i>
                        support@example.com
                    </div>

                    <div class="mb-4">
                        <i class="bi bi-clock-fill text-warning"></i>
                        Mon - Sat : 9 AM - 6 PM
                    </div>

                    <hr>

                    <h5 class="fw-bold">
                        Follow Us
                    </h5>

                    <div class="d-flex gap-3 fs-4">

                        <a href="#">
                            <i class="bi bi-facebook"></i>
                        </a>

                        <a href="#">
                            <i class="bi bi-instagram"></i>
                        </a>

                        <a href="#">
                            <i class="bi bi-twitter-x"></i>
                        </a>

                        <a href="#">
                            <i class="bi bi-linkedin"></i>
                        </a>

                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h4 class="fw-bold mb-4">
                        Send Message
                    </h4>

                    @if (session('success'))
                        <div class="alert alert-success rounded-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="sendMessage">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">
                                    Full Name
                                </label>

                                <input type="text" wire:model="name" class="form-control rounded-pill">

                                @error('name')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email" wire:model="email" class="form-control rounded-pill">

                                @error('email')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Phone
                                </label>

                                <input type="text" wire:model="phone" class="form-control rounded-pill">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Subject
                                </label>

                                <input type="text" wire:model="subject" class="form-control rounded-pill">

                                @error('subject')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-12">

                                <label class="form-label">
                                    Message
                                </label>

                                <textarea wire:model="message" rows="6" class="form-control rounded-4"></textarea>

                                @error('message')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <div class="col-12">

                                <button class="btn btn-primary rounded-pill px-5">
                                    Send Message
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4 mt-5">

        <div class="card-body p-0">

            <iframe src="https://maps.google.com/maps?q=Karachi&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%"
                height="400" style="border:0;" loading="lazy">
            </iframe>

        </div>

    </div>

</div>
