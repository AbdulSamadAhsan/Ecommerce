<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

new class extends Component {
    public string $question = '';

    public array $messages = [];

    public array $quickPrompts = ['Check product stock', 'Track my order', 'How can I create support ticket?', 'Return and refund policy', 'Wallet top up help'];

    public function mount(): void
    {
        $this->messages[] = [
            'role' => 'assistant',
            'text' => 'Assalam o Alaikum! I am your MCP Inventory Assistant. Ask me about product stock, order status, support tickets, returns, wallet top ups, or customer help.',
            'tool' => 'assistant_welcome',
        ];
    }

    public function usePrompt(string $prompt): void
    {
        $this->question = $prompt;
        $this->ask();
    }

    public function ask(): void
    {
        $validated = $this->validate([
            'question' => ['required', 'string', 'min:2', 'max:500'],
        ]);

        $question = trim($validated['question']);

        $this->messages[] = [
            'role' => 'user',
            'text' => $question,
            'tool' => null,
        ];

        $response = $this->runMcpRouter($question);

        $this->messages[] = [
            'role' => 'assistant',
            'text' => $response['text'],
            'tool' => $response['tool'],
        ];

        $this->reset('question');
    }

    private function runMcpRouter(string $question): array
    {
        $q = str($question)->lower()->toString();

        if (str_contains($q, 'stock') || str_contains($q, 'product') || str_contains($q, 'price') || str_contains($q, 'available')) {
            return [
                'tool' => 'inventory.search_products',
                'text' => $this->searchProductsTool($question),
            ];
        }

        if (str_contains($q, 'order') || str_contains($q, 'invoice') || str_contains($q, 'delivery') || str_contains($q, 'track')) {
            return [
                'tool' => 'orders.track_order',
                'text' => $this->trackOrderTool($question),
            ];
        }

        if (str_contains($q, 'ticket') || str_contains($q, 'support') || str_contains($q, 'complaint') || str_contains($q, 'help')) {
            return [
                'tool' => 'support.customer_help',
                'text' => $this->supportTool(),
            ];
        }

        if (str_contains($q, 'return') || str_contains($q, 'refund') || str_contains($q, 'replace') || str_contains($q, 'warranty')) {
            return [
                'tool' => 'policy.returns_refunds',
                'text' => $this->returnPolicyTool(),
            ];
        }

        if (str_contains($q, 'wallet') || str_contains($q, 'topup') || str_contains($q, 'top up') || str_contains($q, 'payment')) {
            return [
                'tool' => 'wallet.topup_help',
                'text' => $this->walletTool(),
            ];
        }

        return [
            'tool' => 'assistant.general_help',
            'text' => "I can help with inventory, product stock, prices, order tracking, customer support tickets, returns, refunds, wallet top ups, and checkout help.\n\nTry asking: `Is MacBook available?`, `Track order 12`, or `How do I create a support ticket?`",
        ];
    }

    private function searchProductsTool(string $question): string
    {
        if (!Schema::hasTable('products')) {
            return 'Products table is not available yet. Please run your migrations first.';
        }

        $columns = Schema::getColumnListing('products');
        $nameColumn = $this->firstExistingColumn($columns, ['name', 'title']);
        $skuColumn = $this->firstExistingColumn($columns, ['sku', 'code']);
        $priceColumn = $this->firstExistingColumn($columns, ['selling_price', 'price', 'sale_price']);
        $stockColumn = $this->firstExistingColumn($columns, ['stock', 'quantity', 'qty']);
        $statusColumn = $this->firstExistingColumn($columns, ['status', 'is_active']);

        if (!$nameColumn) {
            return 'Product search needs a `name` or `title` column in the products table.';
        }

        $term = $this->cleanSearchTerm($question);

        $products = DB::table('products')
            ->when($statusColumn, function ($query) use ($statusColumn) {
                $query->where($statusColumn, 1);
            })
            ->where(function ($query) use ($nameColumn, $skuColumn, $term) {
                $query->where($nameColumn, 'like', '%' . $term . '%');

                if ($skuColumn) {
                    $query->orWhere($skuColumn, 'like', '%' . $term . '%');
                }
            })
            ->latest('id')
            ->limit(5)
            ->get();

        if ($products->isEmpty()) {
            return "I could not find a product matching `{$term}`. Try the product name, SKU, or category keyword.";
        }

        $lines = ["I found these products for `{$term}`:"];

        foreach ($products as $product) {
            $name = $product->{$nameColumn};
            $price = $priceColumn ? number_format((float) $product->{$priceColumn}) : 'N/A';
            $stock = $stockColumn ? $product->{$stockColumn} : 'N/A';

            $lines[] = "- {$name} | Price: {$price} | Stock: {$stock}";
        }

        return implode("\n", $lines);
    }

    private function trackOrderTool(string $question): string
    {
        if (!Schema::hasTable('orders')) {
            return 'Orders table is not available yet. Please run your order migrations first.';
        }

        if (!preg_match('/\d+/', $question, $matches)) {
            return 'Please send your order number. Example: `Track order 12`.';
        }

        $orderNumber = $matches[0];
        $columns = Schema::getColumnListing('orders');
        $statusColumn = $this->firstExistingColumn($columns, ['order_status', 'status']);
        $totalColumn = $this->firstExistingColumn($columns, ['total', 'grand_total', 'net_total']);
        $paymentColumn = $this->firstExistingColumn($columns, ['payment_status', 'payment_method']);

        $query = DB::table('orders')->where(function ($query) use ($columns, $orderNumber) {
            if (in_array('id', $columns, true)) {
                $query->orWhere('id', $orderNumber);
            }

            foreach (['order_no', 'order_number', 'invoice_no', 'tracking_no'] as $column) {
                if (in_array($column, $columns, true)) {
                    $query->orWhere($column, $orderNumber);
                }
            }
        });

        if (!$this->applyCustomerScope($query, $columns)) {
            return 'For privacy, order tracking needs a `user_id` or `customer_id` column so customers can only see their own orders.';
        }

        $order = $query->first();

        if (!$order) {
            return "I could not find order `{$orderNumber}` in your account.";
        }

        $status = $statusColumn ? $order->{$statusColumn} : 'N/A';
        $total = $totalColumn ? number_format((float) $order->{$totalColumn}) : 'N/A';
        $payment = $paymentColumn ? $order->{$paymentColumn} : 'N/A';

        return "Order `{$orderNumber}` status: {$status}\nTotal: {$total}\nPayment: {$payment}";
    }

    private function supportTool(): string
    {
        $ticketRoute = route('customer.support.ticket');
        $myTicketsRoute = route('customer.my.support.tickets');

        return "For support, you can create a customer ticket here: {$ticketRoute}\n\nTo check your previous tickets, open: {$myTicketsRoute}\n\nPlease include your order number, product name, issue details, and screenshot if needed.";
    }

    private function returnPolicyTool(): string
    {
        return "Return/refund help:\n- Keep your order number ready.\n- Product should be unused and in original condition.\n- For damaged/wrong products, create a support ticket immediately.\n- Support team can verify order details and guide replacement or refund.\n\nOpen customer support from your dashboard to start the request.";
    }

    private function walletTool(): string
    {
        $walletRoute = route('customer.wallet');

        return "Wallet help:\n- Open wallet page: {$walletRoute}\n- Submit top up request if wallet top up is enabled.\n- Wait for admin approval before using wallet balance.\n- If payment is deducted but balance is not updated, create a support ticket.";
    }

    private function cleanSearchTerm(string $question): string
    {
        $term = preg_replace('/\b(stock|price|product|available|availability|do you have|show|find|check|please|kia|hai|available hai)\b/i', '', $question);
        $term = trim((string) preg_replace('/\s+/', ' ', $term));

        return $term !== '' ? $term : trim($question);
    }

    private function firstExistingColumn(array $columns, array $names): ?string
    {
        foreach ($names as $name) {
            if (in_array($name, $columns, true)) {
                return $name;
            }
        }

        return null;
    }

    private function applyCustomerScope($query, array $columns): bool
    {
        if (!Auth::check()) {
            return false;
        }

        if (in_array('user_id', $columns, true)) {
            $query->where('user_id', Auth::id());
            return true;
        }

        if (in_array('customer_id', $columns, true)) {
            $query->where('customer_id', Auth::id());
            return true;
        }

        return false;
    }
};

?>


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
                                <button type="button" wire:click="usePrompt('{{ $prompt }}')"
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
                                            @if (!empty($message['tool']))
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
                            <input type="text" wire:model.defer="question"
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
