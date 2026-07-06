<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>All Orders Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        .header {
            border-bottom: 2px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .company {
            font-size: 22px;
            font-weight: bold;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 18px 0;
            text-transform: uppercase;
        }

        .muted {
            color: #6b7280;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #111827;
            color: white;
        }

        .summary th {
            background: #f3f4f6;
            color: #111827;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: bold;
            background: #e5e7eb;
        }

        .footer {
            position: fixed;
            bottom: 12px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="company">Tech Store</div>
        <div class="muted">Karachi, Pakistan | 0300-0000000 | info@example.com</div>
    </div>

    <div class="title">All Orders Report</div>

    <table class="summary" style="margin-bottom: 18px;">
        <tr>
            <th>Total Orders</th>
            <td>{{ $orders->count() }}</td>

            <th>Total Sales</th>
            <td>
                Rs.
                {{ number_format(
                    $orders->filter(fn($order) => optional($order->shipment)->status === 'delivered')->sum(fn($order) => $order->sale->total_amount ?? 0),
                    2,
                ) }}
            </td>

            <th>Completed Orders</th>
            <td>
                {{ $orders->filter(fn($order) => optional($order->shipment)->status === 'delivered')->count() }}
            </td>



        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Order ID</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Items</th>


                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>#{{ $order->id }}</td>

                    <td>{{ $order->created_at->format('d M Y') }}</td>

                    <td>{{ $order->customer->user->name ?? 'N/A' }}</td>

                    <td>{{ $order->customer->phone ?? 'N/A' }}</td>

                    <td>
                        @foreach ($order->sale->items as $item)
                            • {{ $item->product->name }}
                            ({{ $item->quantity ?? $item->qty }})
                            <br>
                        @endforeach
                    </td>


                    <td class="text-right">
                        Rs. {{ number_format($order->sale->total_amount ?? 0, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align:center;">
                        No orders found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y h:i A') }} | Computer generated report.
    </div>

</body>

</html>
