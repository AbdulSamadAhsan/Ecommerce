<?php

namespace App\Livewire\Chat;

use App\Models\ChatThread;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserChat extends Component
{
    public ?int $threadId = null;
    public string $message = '';

    public function mount(): void
    {
        abort_unless(Auth::check(), 403);

        $thread = ChatThread::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'subject' => 'Customer Support Chat',
                'status' => 'open',
                'last_message_at' => now(),
            ]
        );

        $this->threadId = $thread->id;

        $this->markAdminMessagesAsRead();
    }

    public function sendMessage(): void
    {
        abort_unless(Auth::check(), 403);

        $validated = $this->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $thread = $this->thread;

        if (! $thread) {
            return;
        }

        $thread->messages()->create([
            'sender_id' => Auth::id(),
            'message' => trim($validated['message']),
        ]);

        $thread->update([
            'status' => 'open',
            'last_message_at' => now(),
        ]);

        $this->reset('message');
    }

    public function getThreadProperty(): ?ChatThread
    {
        if (! $this->threadId) {
            return null;
        }

        return ChatThread::query()
            ->with(['admin', 'user'])
            ->find($this->threadId);
    }

    public function getMessagesProperty()
    {
        if (! $this->threadId) {
            return collect();
        }

        return ChatThread::find($this->threadId)?->messages()
            ->with('sender')
            ->oldest()
            ->get() ?? collect();
    }

    private function markAdminMessagesAsRead(): void
    {
        if (! $this->threadId) {
            return;
        }

        ChatThread::find($this->threadId)?->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', Auth::id())
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        $this->markAdminMessagesAsRead();

        return view('livewire.chat.user-chat')
            ->layout('components.layouts.ecommerce');
    }
}
