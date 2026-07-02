<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Order Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #111827;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .company {
            font-size: 22px;
            font-weight: bold;
        }

        .muted {
            color: #6b7280;
            font-size: 11px;
        }

        .title {
            text-align: center;
            font-size: 21px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            background: #111827;
            color: white;
            padding: 8px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
        }

        .no-border td {
            border: none;
            padding: 4px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            background: #e5e7eb;
            font-weight: bold;
        }

        .paid {
            background: #dcfce7;
            color: #166534;
        }

        .unpaid {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            position: fixed;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>

    <table class="header no-border">
        <tr>
            <td width="25%">
                @if (file_exists(public_path('images/logo.png')))
                    <img src="{{ public_path('images/logo.png') }}" class="logo">
                @endif
            </td>

            <td width="75%" class="text-right">
                <div class="company">Tech Store</div>
                <div class="muted">Karachi, Pakistan</div>
                <div class="muted">Phone: 0300-0000000</div>
                <div class="muted">Email: info@example.com</div>
            </td>
        </tr>
    </table>

    <div class="title">Order Report</div>

    <div class="section">
        <div class="section-title">Order Details</div>

        <table>
            <tr>
                <th width="25%">Order ID</th>
                <td width="25%">#{{ $order->id }}</td>

                <th width="25%">Order Date</th>
                <td width="25%">{{ $order->created_at?->format('d M Y h:i A') ?? 'N/A' }}</td>
            </tr>

            <tr>
                <th>Order Status</th>
                <td>
                    <span class="badge">
                        {{ ucfirst($order->status ?? 'N/A') }}
                    </span>
                </td>

                <th>Payment Status</th>
                <td>
                    <span class="badge {{ optional($order->sale)->payment_status === 'paid' ? 'paid' : 'unpaid' }}">
                        {{ ucfirst(optional($order->sale)->payment_status ?? 'N/A') }}
                    </span>
                </td>
            </tr>

            <tr>
                <th>Payment Method</th>
                <td>{{ ucfirst(optional($order->sale)->payment_method ?? ($order->payment_method ?? 'N/A')) }}</td>

                <th>Total Amount</th>
                <td>
                    Rs. {{ number_format(optional($order->sale)->total_amount ?? ($order->total ?? 0), 2) }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Customer Details</div>

        <table>
            <tr>
                <th width="25%">Name</th>
                <td width="25%">
                    {{ $order->customer->user->name ?? ($order->customer->name ?? 'N/A') }}
                </td>

                <th width="25%">Email</th>
                <td width="25%">
                    {{ $order->customer->user->email ?? ($order->customer->email ?? 'N/A') }}
                </td>
            </tr>

            <tr>
                <th>Phone</th>
                <td>{{ $order->customer->phone ?? 'N/A' }}</td>

                <th>City</th>
                <td>{{ $order->customer->city ?? 'N/A' }}</td>
            </tr>

            <tr>
                <th>Address</th>
                <td colspan="3">
                    {{ $order->customer->address ?? ($order->address ?? 'N/A') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Shipment Details</div>

        <table>
            <tr>
                <th width="25%">Shipment No</th>
                <td width="25%">{{ $order->shipment->shipment_no ?? 'N/A' }}</td>

                <th width="25%">Tracking No</th>
                <td width="25%">{{ $order->shipment->tracking_number ?? 'N/A' }}</td>
            </tr>

            <tr>
                <th>Shipping Method</th>
                <td>{{ $order->shipment->shippingMethod->name ?? 'N/A' }}</td>

                <th>Shipment Status</th>
                <td>{{ ucfirst($order->shipment->status ?? 'N/A') }}</td>
            </tr>

            <tr>
                <th>Expected Delivery</th>
                <td>
                    @if (optional($order->shipment)->expected_delivery_date)
                        {{ \Carbon\Carbon::parse($order->shipment->expected_delivery_date)->format('d M Y') }}
                    @else
                        N/A
                    @endif
                </td>

                <th>Delivered At</th>
                <td>
                    @if (optional($order->shipment)->delivered_at)
                        {{ \Carbon\Carbon::parse($order->shipment->delivered_at)->format('d M Y h:i A') }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Delivery Boy Details</div>

        @if (optional($order->shipment)->deliveryBoy)
            <table>
                <tr>
                    <th width="25%">Name</th>
                    <td width="25%">{{ $order->shipment->deliveryBoy->name ?? 'N/A' }}</td>

                    <th width="25%">Phone</th>
                    <td width="25%">{{ $order->shipment->deliveryBoy->phone ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td>{{ $order->shipment->deliveryBoy->email ?? 'N/A' }}</td>

                    <th>CNIC</th>
                    <td>{{ $order->shipment->deliveryBoy->cnic ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Vehicle Type</th>
                    <td>{{ $order->shipment->deliveryBoy->vehicle_type ?? 'N/A' }}</td>

                    <th>Vehicle Number</th>
                    <td>{{ $order->shipment->deliveryBoy->vehicle_number ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>License Number</th>
                    <td>{{ $order->shipment->deliveryBoy->license_number ?? 'N/A' }}</td>

                    <th>Status</th>
                    <td>{{ ucfirst($order->shipment->deliveryBoy->status ?? 'active') }}</td>
                </tr>
            </table>
        @else
            <table>
                <tr>
                    <td class="text-center">
                        <strong>Delivery boy is not assigned yet.</strong>
                    </td>
                </tr>
            </table>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Order Items</div>

        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Product</th>
                    <th width="18%">SKU</th>
                    <th width="10%">Qty</th>
                    <th width="15%">Price</th>
                    <th width="15%">Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @forelse($order->sale->items ?? $order->items ?? [] as $item)
                    @php
                        $qty = $item->quantity ?? ($item->qty ?? 0);
                        $price = $item->price ?? ($item->sale_price ?? 0);
                        $subtotal = $qty * $price;
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->product->sku ?? 'N/A' }}</td>
                        <td>{{ $qty }}</td>
                        <td>Rs. {{ number_format($price, 2) }}</td>
                        <td>Rs. {{ number_format($subtotal, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td width="65%" rowspan="5">
                    <strong>Notes:</strong><br>
                    {{ $order->notes ?? 'Thank you for your order.' }}
                </td>

                <th>Subtotal</th>
                <td class="text-right">
                    Rs.
                    {{ number_format(optional($order->sale)->subtotal ?? (optional($order->sale)->total_amount ?? ($order->total ?? 0)), 2) }}
                </td>
            </tr>

            <tr>
                <th>Discount</th>
                <td class="text-right">
                    Rs. {{ number_format(optional($order->sale)->discount ?? ($order->discount ?? 0), 2) }}
                </td>
            </tr>

            <tr>
                <th>Shipping</th>
                <td class="text-right">
                    Rs.
                    {{ number_format(optional($order->sale)->shipping_charge ?? ($order->shipping_charge ?? 0), 2) }}
                </td>
            </tr>

            <tr>
                <th>Tax</th>
                <td class="text-right">
                    Rs. {{ number_format(optional($order->sale)->tax ?? ($order->tax ?? 0), 2) }}
                </td>
            </tr>

            <tr>
                <th>Grand Total</th>
                <td class="text-right">
                    <strong>
                        Rs. {{ number_format(optional($order->sale)->total_amount ?? ($order->total ?? 0), 2) }}
                    </strong>
                </td>
            </tr>
        </table>
    </div>

    <br>


    <div class="footer">
        Generated on {{ now()->format('d M Y h:i A') }} |
        This is a computer generated report.
    </div>

</body>

</html>
