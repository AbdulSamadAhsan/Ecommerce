<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h3 class="fw-bold mb-1">
                                <i class="bi bi-robot me-2"></i>
                                MCP Inventory Assistant
                            </h3>
                            <p class="mb-0 opacity-75">
                                Customer AI help for stock, orders, support tickets, returns and wallet top ups.
                            </p>
                        </div>

                        <span class="badge bg-light text-primary rounded-pill px-3 py-2">
                            <i class="bi bi-plug-fill me-1"></i>
                            MCP Tools Enabled
                        </span>
                    </div>
                </div>

                <div class="card-body bg-light p-3 p-md-4">
                    <div class="row g-3 mb-4">
                        @foreach ($quickPrompts as $prompt)
                            <div class="col-12 col-md-6 col-lg">
                                <button type="button"
                                        wire:click="usePrompt('{{ $prompt }}')"
                                        class="btn btn-white border shadow-sm rounded-pill w-100">
                                    {{ $prompt }}
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-white border rounded-4 p-3 p-md-4" style="height: 520px; overflow-y: auto;">
                        @foreach ($messages as $index => $message)
                            <div wire:key="assistant-message-{{ $index }}"
                                 class="d-flex mb-3 {{ $message['role'] === 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="{{ $message['role'] === 'user' ? 'bg-primary text-white' : 'bg-light text-dark border' }} rounded-4 p-3 shadow-sm"
                                     style="max-width: 82%;">
                                    <div class="small fw-semibold mb-2 opacity-75">
                                        @if ($message['role'] === 'user')
                                            <i class="bi bi-person-circle me-1"></i> You
                                        @else
                                            <i class="bi bi-cpu me-1"></i> Assistant
                                            @if (! empty($message['tool']))
                                                <span class="badge bg-secondary ms-2">{{ $message['tool'] }}</span>
                                            @endif
                                        @endif
                                    </div>

                                    <div style="white-space: pre-line;">
                                        {!! nl2br(e($message['text'])) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form wire:submit.prevent="ask" class="mt-4">
                        <div class="input-group input-group-lg shadow-sm">
                            <input type="text"
                                   wire:model.defer="question"
                                   class="form-control border-0 rounded-start-pill"
                                   placeholder="Ask: Is iPhone available? Track order 12...">

                            <button class="btn btn-primary rounded-end-pill px-4" type="submit">
                                <span wire:loading.remove wire:target="ask">
                                    <i class="bi bi-send-fill me-1"></i> Ask
                                </span>
                                <span wire:loading wire:target="ask">
                                    <span class="spinner-border spinner-border-sm me-1"></span> Thinking
                                </span>
                            </button>
                        </div>

                        @error('question')
                            <div class="text-danger small mt-2 ms-3">{{ $message }}</div>
                        @enderror
                    </form>
                </div>
            </div>

            <div class="alert alert-info border-0 rounded-4 mt-4 shadow-sm">
                <strong>How this MCP assistant works:</strong>
                user question → MCP router → inventory/order/support/wallet tool → safe customer response.
            </div>
        </div>
    </div>
</div>
