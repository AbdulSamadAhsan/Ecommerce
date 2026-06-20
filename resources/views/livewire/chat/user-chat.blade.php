<div wire:poll.3s>
    <style>
        .customer-chat-wrapper {
            max-width: 920px;
            margin: 0 auto;
        }

        .customer-chat-card {
            background: #fff;
            border-radius: 26px;
            box-shadow: 0 12px 35px rgba(15, 23, 42, .08);
            overflow: hidden;
        }

        .customer-chat-header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            padding: 22px;
        }

        .customer-chat-messages {
            height: 60vh;
            overflow-y: auto;
            background: #f8fafc;
            padding: 22px;
        }

        .message-bubble {
            max-width: 76%;
            border-radius: 18px;
            padding: 12px 15px;
            line-height: 1.45;
            word-wrap: break-word;
        }

        .message-me {
            background: #2563eb;
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .message-admin {
            background: #fff;
            color: #111827;
            border: 1px solid #e5e7eb;
            border-bottom-left-radius: 4px;
        }
    </style>

    <div class="container customer-chat-wrapper py-4">
        <div class="customer-chat-card">
            <div class="customer-chat-header d-flex justify-content-between align-items-center gap-3">
                <div>
                    <h3 class="fw-bold mb-1">
                        <i class="bi bi-headset me-2"></i>
                        Support Chat
                    </h3>
                    <p class="mb-0 text-white-50">Send a message to admin support.</p>
                </div>

                <span class="badge {{ $this->thread?->status === 'open' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-2">
                    {{ ucfirst($this->thread?->status ?? 'open') }}
                </span>
            </div>

            <div class="customer-chat-messages">
                @forelse ($this->messages as $chatMessage)
                    @php($mine = $chatMessage->sender_id === auth()->id())

                    <div class="d-flex mb-3 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="message-bubble {{ $mine ? 'message-me' : 'message-admin' }}">
                            <div>{{ $chatMessage->message }}</div>
                            <small class="d-block mt-1 {{ $mine ? 'text-white-50' : 'text-muted' }}">
                                {{ $mine ? 'You' : ($chatMessage->sender?->name ?? 'Admin') }} ·
                                {{ $chatMessage->created_at->format('d M, h:i A') }}
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-chat-dots fs-1 d-block mb-3"></i>
                        <h5 class="fw-bold">Start your first conversation</h5>
                        <p class="mb-0">Ask admin about products, orders, delivery, or support.</p>
                    </div>
                @endforelse
            </div>

            <form wire:submit.prevent="sendMessage" class="p-3 bg-white border-top">
                <div class="input-group">
                    <textarea wire:model.defer="message" class="form-control rounded-start-4" rows="2"
                        placeholder="Write your message..."></textarea>
                    <button type="submit" class="btn btn-primary px-4 rounded-end-4">
                        <i class="bi bi-send-fill me-1"></i>
                        Send
                    </button>
                </div>

                @error('message')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </form>
        </div>
    </div>
</div>
