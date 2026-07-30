<?php

use Livewire\Component;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Mail;

new class extends Component {
    public ContactUs $contact;

    public $reply = '';

    public function mount($id)
    {
        $this->contact = ContactUs::findOrFail($id);
    }

    protected function rules()
    {
        return [
            'reply' => 'required|min:5|max:5000',
        ];
    }

    public function sendReply()
    {
        $this->validate();

        Mail::raw($this->reply, function ($mail) {
            $mail->to($this->contact->email)->subject('Re: ' . $this->contact->subject);
        });

        $this->contact->update([
            'status' => 'replied',
        ]);

        session()->flash('success', 'Reply sent successfully.');

        $this->reply = '';

        $this->contact->refresh();
    }
};

?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Reply to Customer</h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-3">
                    <strong>Name:</strong> {{ $contact->name }}
                </div>

                <div class="mb-3">
                    <strong>Email:</strong> {{ $contact->email }}
                </div>

                <div class="mb-3">
                    <strong>Subject:</strong> {{ $contact->subject }}
                </div>

                <div class="mb-4">

                    <strong>Customer Message</strong>

                    <div class="border rounded p-3 bg-light mt-2">
                        {{ $contact->message }}
                    </div>

                </div>

                <form wire:submit="sendReply">

                    <div class="mb-4">

                        <label class="form-label">
                            Your Reply
                        </label>

                        <textarea rows="8" class="form-control @error('reply') is-invalid @enderror" wire:model.live="reply"
                            placeholder="Write your reply..."></textarea>

                        @error('reply')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="text-end">

                        <button class="btn btn-success" wire:loading.attr="disabled">

                            <span wire:loading.remove>
                                Send Reply
                            </span>

                            <span wire:loading>
                                Sending...
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
