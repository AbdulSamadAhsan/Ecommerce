<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

new class extends Component {
    public string $question = '';
    public array $messages = [];

    public array $quickPrompts = ['Show dashboard stats', 'Which products are low stock?', 'Show latest products', 'How many products?', 'Track order 12', 'Show categories', 'Show suppliers', 'Wallet top up help', 'Return and refund policy', 'Support ticket help'];

    public function mount(): void
    {
        $this->messages[] = [
            'role' => 'assistant',
            'text' => 'Assalam o Alaikum! I am your free MCP Inventory Assistant. Ask me about products, stock, orders, customers, suppliers, categories, sales, wallet, support, returns, or dashboard stats.',
            'tool' => 'welcome',
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

        $response = $this->runFreeMcpAssistant($question);

        $this->messages[] = [
            'role' => 'assistant',
            'text' => $response['text'],
            'tool' => $response['tool'],
        ];

        $this->reset('question');
    }

    private function runFreeMcpAssistant(string $question): array
    {
        $intent = $this->detectIntent($question);

        return match ($intent) {
            'dashboard_stats' => [
                'tool' => 'dashboard.stats',
                'text' => $this->dashboardStatsTool(),
            ],

            'product_count' => [
                'tool' => 'inventory.product_count',
                'text' => $this->productCountTool(),
            ],

            'latest_products' => [
                'tool' => 'inventory.latest_products',
                'text' => $this->latestProductsTool(),
            ],

            'low_stock' => [
                'tool' => 'inventory.low_stock',
                'text' => $this->lowStockTool(),
            ],

            'product_search' => [
                'tool' => 'inventory.search_products',
                'text' => $this->searchProductsTool($question),
            ],

            'track_order' => [
                'tool' => 'orders.track_order',
                'text' => $this->trackOrderTool($question),
            ],

            'order_count' => [
                'tool' => 'orders.count',
                'text' => $this->orderCountTool(),
            ],

            'categories' => [
                'tool' => 'categories.list',
                'text' => $this->listTableTool('categories', ['name', 'title'], 'Categories'),
            ],

            'brands' => [
                'tool' => 'brands.list',
                'text' => $this->listTableTool('brands', ['name', 'title'], 'Brands'),
            ],

            'suppliers' => [
                'tool' => 'suppliers.list',
                'text' => $this->listTableTool('suppliers', ['name', 'title'], 'Suppliers'),
            ],

            'customers' => [
                'tool' => 'customers.count',
                'text' => $this->countTableTool('customers', 'customers'),
            ],

            'employees' => [
                'tool' => 'employees.count',
                'text' => $this->countTableTool('employees', 'employees'),
            ],

            'wallet' => [
                'tool' => 'wallet.help',
                'text' => $this->walletTool(),
            ],

            'support' => [
                'tool' => 'support.help',
                'text' => $this->supportTool(),
            ],

            'return_refund' => [
                'tool' => 'policy.return_refund',
                'text' => $this->returnPolicyTool(),
            ],

            default => [
                'tool' => 'assistant.help',
                'text' => $this->generalHelpTool(),
            ],
        };
    }

    private function detectIntent(string $question): string
    {
        $q = strtolower($question);

        $patterns = [
            'dashboard_stats' => ['dashboard', 'stats', 'statistics', 'summary', 'overview'],
            'product_count' => ['how many products', 'total products', 'product count'],
            'latest_products' => ['latest products', 'new products', 'recent products'],
            'low_stock' => ['low stock', 'out of stock', 'stock alert', 'less stock'],
            'product_search' => ['product', 'stock', 'price', 'available', 'availability', 'sku'],
            'track_order' => ['track order', 'order status', 'delivery status', 'invoice'],
            'order_count' => ['total orders', 'order count', 'how many orders'],
            'categories' => ['category', 'categories'],
            'brands' => ['brand', 'brands'],
            'suppliers' => ['supplier', 'suppliers'],
            'customers' => ['customer', 'customers'],
            'employees' => ['employee', 'employees', 'staff'],
            'wallet' => ['wallet', 'topup', 'top up', 'payment'],
            'support' => ['support', 'ticket', 'complaint', 'help'],
            'return_refund' => ['return', 'refund', 'replace', 'warranty'],
        ];

        foreach ($patterns as $intent => $words) {
            foreach ($words as $word) {
                if (str_contains($q, $word)) {
                    return $intent;
                }

                similar_text($q, $word, $percent);

                if ($percent >= 65) {
                    return $intent;
                }
            }
        }

        return 'general';
    }

    private function dashboardStatsTool(): string
    {
        $lines = ['Dashboard Summary:'];

        foreach (
            [
                'products' => 'Products',
                'orders' => 'Orders',
                'customers' => 'Customers',
                'categories' => 'Categories',
                'brands' => 'Brands',
                'suppliers' => 'Suppliers',
                'employees' => 'Employees',
                'warehouses' => 'Warehouses',
            ]
            as $table => $label
        ) {
            if (Schema::hasTable($table)) {
                $lines[] = "- {$label}: " . DB::table($table)->count();
            }
        }

        return implode("\n", $lines);
    }

    private function productCountTool(): string
    {
        if (!Schema::hasTable('products')) {
            return 'Products table not found.';
        }

        return 'Total products: ' . DB::table('products')->count();
    }

    private function orderCountTool(): string
    {
        if (!Schema::hasTable('orders')) {
            return 'Orders table not found.';
        }

        return 'Total orders: ' . DB::table('orders')->count();
    }

    private function latestProductsTool(): string
    {
        if (!Schema::hasTable('products')) {
            return 'Products table not found.';
        }

        $columns = Schema::getColumnListing('products');

        $name = $this->firstExistingColumn($columns, ['name', 'title']);
        $price = $this->firstExistingColumn($columns, ['price', 'selling_price', 'sale_price']);
        $stock = $this->firstExistingColumn($columns, ['stock', 'quantity', 'qty']);

        if (!$name) {
            return 'Products table needs name or title column.';
        }

        $products = DB::table('products')->latest('id')->limit(10)->get();

        if ($products->isEmpty()) {
            return 'No products found.';
        }

        $lines = ['Latest products:'];

        foreach ($products as $product) {
            $priceText = $price ? $product->{$price} : 'N/A';
            $stockText = $stock ? $product->{$stock} : 'N/A';

            $lines[] = "- {$product->{$name}} | Price: {$priceText} | Stock: {$stockText}";
        }

        return implode("\n", $lines);
    }

    private function lowStockTool(): string
    {
        if (!Schema::hasTable('products')) {
            return 'Products table not found.';
        }

        $columns = Schema::getColumnListing('products');

        $name = $this->firstExistingColumn($columns, ['name', 'title']);
        $stock = $this->firstExistingColumn($columns, ['stock', 'quantity', 'qty']);
        $price = $this->firstExistingColumn($columns, ['price', 'selling_price', 'sale_price']);

        if (!$name || !$stock) {
            return 'Low stock tool needs product name/title and stock/quantity/qty columns.';
        }

        $products = DB::table('products')->where($stock, '<=', 10)->orderBy($stock)->limit(10)->get();

        if ($products->isEmpty()) {
            return 'No low stock products found.';
        }

        $lines = ['Low stock products:'];

        foreach ($products as $product) {
            $priceText = $price ? $product->{$price} : 'N/A';

            $lines[] = "- {$product->{$name}} | Stock: {$product->{$stock}} | Price: {$priceText}";
        }

        return implode("\n", $lines);
    }

    private function searchProductsTool(string $question): string
    {
        if (!Schema::hasTable('products')) {
            return 'Products table not found.';
        }

        $columns = Schema::getColumnListing('products');

        $name = $this->firstExistingColumn($columns, ['name', 'title']);
        $sku = $this->firstExistingColumn($columns, ['sku', 'code']);
        $price = $this->firstExistingColumn($columns, ['price', 'selling_price', 'sale_price']);
        $stock = $this->firstExistingColumn($columns, ['stock', 'quantity', 'qty']);
        $status = $this->firstExistingColumn($columns, ['status', 'is_active']);

        if (!$name) {
            return 'Product search needs name or title column.';
        }

        $term = $this->cleanSearchTerm($question);

        if ($term === '') {
            return 'Please write product name. Example: Is iPhone available?';
        }

        $products = DB::table('products')
            ->when($status, fn($query) => $query->where($status, 1))
            ->where(function ($query) use ($name, $sku, $term) {
                $query->where($name, 'like', "%{$term}%");

                if ($sku) {
                    $query->orWhere($sku, 'like', "%{$term}%");
                }
            })
            ->latest('id')
            ->limit(5)
            ->get();

        if ($products->isEmpty()) {
            return "No product found for: {$term}";
        }

        $lines = ["Products found for {$term}:"];

        foreach ($products as $product) {
            $priceText = $price ? $product->{$price} : 'N/A';
            $stockText = $stock ? $product->{$stock} : 'N/A';
            $skuText = $sku ? $product->{$sku} : 'N/A';

            $lines[] = "- {$product->{$name}} | SKU: {$skuText} | Price: {$priceText} | Stock: {$stockText}";
        }

        return implode("\n", $lines);
    }

    private function trackOrderTool(string $question): string
    {
        if (!Schema::hasTable('orders')) {
            return 'Orders table not found.';
        }

        if (!preg_match('/\d+/', $question, $matches)) {
            return 'Please send order number. Example: Track order 12';
        }

        $orderNumber = $matches[0];
        $columns = Schema::getColumnListing('orders');

        $status = $this->firstExistingColumn($columns, ['order_status', 'status']);
        $total = $this->firstExistingColumn($columns, ['total', 'grand_total', 'net_total']);
        $payment = $this->firstExistingColumn($columns, ['payment_status', 'payment_method']);

        $query = DB::table('orders')->where('id', $orderNumber);

        if (Auth::check()) {
            if (in_array('user_id', $columns, true)) {
                $query->where('user_id', Auth::id());
            } elseif (in_array('customer_id', $columns, true)) {
                $query->where('customer_id', Auth::id());
            }
        }

        $order = $query->first();

        if (!$order) {
            return "Order {$orderNumber} not found.";
        }

        return "Order #{$orderNumber}\nStatus: " . ($status ? $order->{$status} : 'N/A') . "\nTotal: " . ($total ? $order->{$total} : 'N/A') . "\nPayment: " . ($payment ? $order->{$payment} : 'N/A');
    }

    private function listTableTool(string $table, array $nameColumns, string $label): string
    {
        if (!Schema::hasTable($table)) {
            return "{$label} table not found.";
        }

        $columns = Schema::getColumnListing($table);
        $name = $this->firstExistingColumn($columns, $nameColumns);

        if (!$name) {
            return "{$label} table needs name/title column.";
        }

        $items = DB::table($table)->select('id', $name)->latest('id')->limit(10)->get();

        if ($items->isEmpty()) {
            return "No {$label} found.";
        }

        $lines = ["{$label}:"];

        foreach ($items as $item) {
            $lines[] = "- {$item->{$name}}";
        }

        return implode("\n", $lines);
    }

    private function countTableTool(string $table, string $label): string
    {
        if (!Schema::hasTable($table)) {
            return ucfirst($label) . ' table not found.';
        }

        return 'Total ' . $label . ': ' . DB::table($table)->count();
    }

    private function walletTool(): string
    {
        return "Wallet help:\n- Open wallet page: " . $this->safeRoute('customer.wallet') . "\n- Submit top up request.\n- Wait for admin approval.\n- If payment is deducted but balance not updated, create support ticket.";
    }

    private function supportTool(): string
    {
        return "Support help:\n- Create ticket: " . $this->safeRoute('customer.support.ticket') . "\n- My tickets: " . $this->safeRoute('customer.my.support.tickets') . "\n- Add order number, product name, issue details, and screenshot.";
    }

    private function returnPolicyTool(): string
    {
        return "Return/refund policy:\n- Keep your order number ready.\n- Product should be unused and in original condition.\n- For damaged/wrong product, create support ticket.\n- Admin will verify and guide refund or replacement.";
    }

    private function generalHelpTool(): string
    {
        return "I can help with:\n- Product stock and price\n- Low stock products\n- Latest products\n- Order tracking\n- Dashboard stats\n- Categories, brands, suppliers\n- Wallet top up\n- Support tickets\n- Return and refund policy\n\nExample: `Show low stock products` or `Track order 12`.";
    }

    private function cleanSearchTerm(string $question): string
    {
        $term = preg_replace('/\b(stock|price|product|products|available|availability|show|find|check|please|latest|low|order|track|return|refund|wallet|support|ticket|help|is|are|the|do|you|have)\b/i', '', $question);

        return trim((string) preg_replace('/\s+/', ' ', $term));
    }

    private function firstExistingColumn(array $columns, array $names): ?string
    {
        foreach ($names as $name) {
            if ($name && in_array($name, $columns, true)) {
                return $name;
            }
        }

        return null;
    }

    private function safeRoute(string $name): string
    {
        return Route::has($name) ? route($name) : "Route not found: {$name}";
    }

    public function rendering($view): void
    {
        $view->layout('components.layouts.ecommerce', [
            'cartCount' => 2,
        ]);
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
                                Free MCP Inventory Assistant
                            </h3>
                            <p class="mb-0 opacity-75">
                                Free database assistant for products, stock, orders, wallet, support and reports.
                            </p>
                        </div>

                        <span class="badge bg-light text-primary rounded-pill px-3 py-2">
                            No API • No Package
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
                                placeholder="Ask: Show low stock products, Track order 12...">

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

        </div>
    </div>
</div>
