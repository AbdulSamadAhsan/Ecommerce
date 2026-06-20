<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

new class extends Component {
    public string $question = '';

    public array $messages = [];

    public array $quickPrompts = [
        'Show low stock products',
        'Today sales summary',
        'Pending orders',
        'Inventory health report',
        'Customer support summary',
    ];

    public function mount(): void
    {
        $this->messages[] = [
            'role' => 'assistant',
            'text' => 'Assalam o Alaikum Admin! I am your MCP Inventory Assistant. Ask me about low stock, sales, orders, customers, support tickets, products, suppliers, warehouses, or inventory health.',
            'tool' => 'admin_assistant_welcome',
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

        if (str_contains($q, 'low stock') || str_contains($q, 'minimum stock') || str_contains($q, 'reorder')) {
            return [
                'tool' => 'admin.inventory.low_stock',
                'text' => $this->lowStockTool(),
            ];
        }

        if (str_contains($q, 'stock') || str_contains($q, 'product') || str_contains($q, 'inventory')) {
            return [
                'tool' => 'admin.inventory.health',
                'text' => $this->inventoryHealthTool(),
            ];
        }

        if (str_contains($q, 'sale') || str_contains($q, 'revenue') || str_contains($q, 'income')) {
            return [
                'tool' => 'admin.sales.summary',
                'text' => $this->salesSummaryTool(),
            ];
        }

        if (str_contains($q, 'order') || str_contains($q, 'pending') || str_contains($q, 'delivery')) {
            return [
                'tool' => 'admin.orders.summary',
                'text' => $this->ordersSummaryTool(),
            ];
        }

        if (str_contains($q, 'customer') || str_contains($q, 'user')) {
            return [
                'tool' => 'admin.customers.summary',
                'text' => $this->customersSummaryTool(),
            ];
        }

        if (str_contains($q, 'support') || str_contains($q, 'ticket') || str_contains($q, 'complaint')) {
            return [
                'tool' => 'admin.support.summary',
                'text' => $this->supportSummaryTool(),
            ];
        }

        if (str_contains($q, 'supplier') || str_contains($q, 'warehouse') || str_contains($q, 'department') || str_contains($q, 'employee')) {
            return [
                'tool' => 'admin.erp.summary',
                'text' => $this->erpSummaryTool(),
            ];
        }

        return [
            'tool' => 'admin.assistant.general_help',
            'text' => "I can help admin with inventory, low stock, products, sales, pending orders, customers, support tickets, suppliers, warehouses, employees, and departments.\n\nTry asking: `Show low stock products`, `Today sales summary`, `Pending orders`, or `Inventory health report`.",
        ];
    }

    private function lowStockTool(): string
    {
        if (! Schema::hasTable('products')) {
            return 'Products table is not available. Run product migrations first.';
        }

        $columns = Schema::getColumnListing('products');
        $nameColumn = $this->firstExistingColumn($columns, ['name', 'title']);
        $stockColumn = $this->firstExistingColumn($columns, ['stock', 'quantity', 'qty']);
        $minimumColumn = $this->firstExistingColumn($columns, ['minimum_stock', 'min_stock', 'reorder_level']);
        $priceColumn = $this->firstExistingColumn($columns, ['selling_price', 'price', 'sale_price']);

        if (! $nameColumn || ! $stockColumn) {
            return 'Low stock report needs product `name/title` and `stock/quantity/qty` columns.';
        }

        $products = DB::table('products')
            ->when($minimumColumn, function ($query) use ($stockColumn, $minimumColumn) {
                $query->whereColumn($stockColumn, '<=', $minimumColumn);
            }, function ($query) use ($stockColumn) {
                $query->where($stockColumn, '<=', 5);
            })
            ->orderBy($stockColumn)
            ->limit(10)
            ->get();

        if ($products->isEmpty()) {
            return 'Great! No low stock products found right now.';
        }

        $lines = ['Low stock products:'];

        foreach ($products as $product) {
            $name = $product->{$nameColumn};
            $stock = $product->{$stockColumn};
            $minimum = $minimumColumn ? $product->{$minimumColumn} : 5;
            $price = $priceColumn ? number_format((float) $product->{$priceColumn}) : 'N/A';

            $lines[] = "- {$name} | Stock: {$stock} | Min: {$minimum} | Price: {$price}";
        }

        return implode("\n", $lines);
    }

    private function inventoryHealthTool(): string
    {
        if (! Schema::hasTable('products')) {
            return 'Products table is not available. Run product migrations first.';
        }

        $columns = Schema::getColumnListing('products');
        $stockColumn = $this->firstExistingColumn($columns, ['stock', 'quantity', 'qty']);
        $minimumColumn = $this->firstExistingColumn($columns, ['minimum_stock', 'min_stock', 'reorder_level']);
        $statusColumn = $this->firstExistingColumn($columns, ['status', 'is_active']);

        $totalProducts = DB::table('products')->count();
        $activeProducts = $statusColumn ? DB::table('products')->where($statusColumn, 1)->count() : 'N/A';
        $totalStock = $stockColumn ? DB::table('products')->sum($stockColumn) : 'N/A';

        $lowStock = 'N/A';
        if ($stockColumn) {
            $lowStockQuery = DB::table('products');

            if ($minimumColumn) {
                $lowStockQuery->whereColumn($stockColumn, '<=', $minimumColumn);
            } else {
                $lowStockQuery->where($stockColumn, '<=', 5);
            }

            $lowStock = $lowStockQuery->count();
        }

        return "Inventory health:\n- Total products: {$totalProducts}\n- Active products: {$activeProducts}\n- Total stock quantity: {$totalStock}\n- Low stock products: {$lowStock}";
    }

    private function salesSummaryTool(): string
    {
        $table = Schema::hasTable('sales') ? 'sales' : (Schema::hasTable('orders') ? 'orders' : null);

        if (! $table) {
            return 'Sales/orders table is not available. Run sales or orders migrations first.';
        }

        $columns = Schema::getColumnListing($table);
        $totalColumn = $this->firstExistingColumn($columns, ['total', 'grand_total', 'net_total', 'amount']);
        $dateColumn = $this->firstExistingColumn($columns, ['created_at', 'sale_date', 'order_date']);

        $totalRecords = DB::table($table)->count();
        $totalRevenue = $totalColumn ? DB::table($table)->sum($totalColumn) : 0;

        $todayRecords = 'N/A';
        $todayRevenue = 'N/A';

        if ($dateColumn) {
            $todayRecords = DB::table($table)->whereDate($dateColumn, today())->count();
            $todayRevenue = $totalColumn
                ? number_format((float) DB::table($table)->whereDate($dateColumn, today())->sum($totalColumn))
                : 'N/A';
        }

        return "Sales summary from `{$table}`:\n- Total records: {$totalRecords}\n- Total revenue: " . number_format((float) $totalRevenue) . "\n- Today records: {$todayRecords}\n- Today revenue: {$todayRevenue}";
    }

    private function ordersSummaryTool(): string
    {
        if (! Schema::hasTable('orders')) {
            return 'Orders table is not available. Run order migrations first.';
        }

        $columns = Schema::getColumnListing('orders');
        $statusColumn = $this->firstExistingColumn($columns, ['order_status', 'status']);
        $totalColumn = $this->firstExistingColumn($columns, ['total', 'grand_total', 'net_total']);

        $totalOrders = DB::table('orders')->count();
        $pendingOrders = $statusColumn
            ? DB::table('orders')->where($statusColumn, 'like', '%pending%')->count()
            : 'N/A';
        $totalValue = $totalColumn ? number_format((float) DB::table('orders')->sum($totalColumn)) : 'N/A';

        return "Orders summary:\n- Total orders: {$totalOrders}\n- Pending orders: {$pendingOrders}\n- Total order value: {$totalValue}";
    }

    private function customersSummaryTool(): string
    {
        if (! Schema::hasTable('customers')) {
            return 'Customers table is not available. Run customer migrations first.';
        }

        $columns = Schema::getColumnListing('customers');
        $statusColumn = $this->firstExistingColumn($columns, ['status', 'is_active']);
        $dateColumn = $this->firstExistingColumn($columns, ['created_at', 'registered_at']);

        $totalCustomers = DB::table('customers')->count();
        $activeCustomers = $statusColumn ? DB::table('customers')->where($statusColumn, 1)->count() : 'N/A';
        $todayCustomers = $dateColumn ? DB::table('customers')->whereDate($dateColumn, today())->count() : 'N/A';

        return "Customers summary:\n- Total customers: {$totalCustomers}\n- Active customers: {$activeCustomers}\n- New customers today: {$todayCustomers}";
    }

    private function supportSummaryTool(): string
    {
        $table = $this->firstExistingTable(['customer_support_tickets', 'support_tickets', 'tickets']);

        if (! $table) {
            return 'Support ticket table is not available yet. Expected table: customer_support_tickets, support_tickets, or tickets.';
        }

        $columns = Schema::getColumnListing($table);
        $statusColumn = $this->firstExistingColumn($columns, ['status', 'ticket_status']);
        $dateColumn = $this->firstExistingColumn($columns, ['created_at', 'ticket_date']);

        $totalTickets = DB::table($table)->count();
        $openTickets = $statusColumn
            ? DB::table($table)->whereIn($statusColumn, ['open', 'pending', 'new'])->count()
            : 'N/A';
        $todayTickets = $dateColumn ? DB::table($table)->whereDate($dateColumn, today())->count() : 'N/A';

        return "Support summary from `{$table}`:\n- Total tickets: {$totalTickets}\n- Open/pending tickets: {$openTickets}\n- New tickets today: {$todayTickets}";
    }

    private function erpSummaryTool(): string
    {
        $tables = [
            'suppliers' => Schema::hasTable('suppliers') ? DB::table('suppliers')->count() : 'N/A',
            'warehouses' => Schema::hasTable('warehouses') ? DB::table('warehouses')->count() : 'N/A',
            'departments' => Schema::hasTable('departments') ? DB::table('departments')->count() : 'N/A',
            'employees' => Schema::hasTable('employees') ? DB::table('employees')->count() : 'N/A',
        ];

        return "ERP summary:\n- Suppliers: {$tables['suppliers']}\n- Warehouses: {$tables['warehouses']}\n- Departments: {$tables['departments']}\n- Employees: {$tables['employees']}";
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

    private function firstExistingTable(array $tables): ?string
    {
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }
};

?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h3 class="fw-bold mb-1">
                                <i class="bi bi-robot me-2"></i>
                                Admin MCP Inventory Assistant
                            </h3>
                            <p class="mb-0 text-white-50">
                                Admin AI help for inventory, sales, orders, customers, ERP and CRM summaries.
                            </p>
                        </div>

                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            <i class="bi bi-tools me-1"></i>
                            Admin MCP Tools
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

                    <div class="bg-white border rounded-4 p-3 p-md-4" style="height: 540px; overflow-y: auto;">
                        @foreach ($messages as $index => $message)
                            <div wire:key="admin-assistant-message-{{ $index }}"
                                 class="d-flex mb-3 {{ $message['role'] === 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="{{ $message['role'] === 'user' ? 'bg-dark text-white' : 'bg-light text-dark border' }} rounded-4 p-3 shadow-sm"
                                     style="max-width: 84%;">
                                    <div class="small fw-semibold mb-2 opacity-75">
                                        @if ($message['role'] === 'user')
                                            <i class="bi bi-person-circle me-1"></i> Admin
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
                                   placeholder="Ask: Show low stock products, today sales summary, pending orders...">

                            <button class="btn btn-dark rounded-end-pill px-4" type="submit">
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

            <div class="alert alert-dark border-0 rounded-4 mt-4 shadow-sm">
                <strong>Admin MCP Flow:</strong>
                admin question → MCP router → inventory/sales/orders/customers/support/ERP tool → dashboard answer.
            </div>
        </div>
    </div>
</div>
