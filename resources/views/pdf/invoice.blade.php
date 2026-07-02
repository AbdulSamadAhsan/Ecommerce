<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #111827;
            margin: 0;
            padding: 0;
        }

        .invoice-box {
            padding: 25px;
        }

        .header-table,
        .info-table,
        .summary-table,
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        .header-table td,
        .info-table td,
        .summary-table td,
        .footer-table td {
            border: none;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 4px;
        }

        .invoice-title {
            font-size: 26px;
            font-weight: bold;
            text-align: right;
            color: #111827;
        }

        .invoice-no {
            text-align: right;
            color: #6b7280;
            font-size: 13px;
        }

        .section-title {
            background: #1d4ed8;
            color: #ffffff;
            padding: 8px 10px;
            font-weight: bold;
            margin-top: 18px;
            margin-bottom: 0;
        }

        .info-box {
            border: 1px solid #e5e7eb;
            padding: 10px;
            min-height: 105px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        .items-table th {
            background: #f3f4f6;
            color: #111827;
            border: 1px solid #d1d5db;
            padding: 9px;
            font-weight: bold;
            text-align: left;
        }

        .items-table td {
            border: 1px solid #e5e7eb;
            padding: 9px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary-wrapper {
            width: 40%;
            margin-left: auto;
            margin-top: 18px;
        }

        .summary-table {
            border-collapse: collapse;
            width: 100%;
        }

        .summary-table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
        }

        .summary-table .label {
            background: #f9fafb;
            font-weight: bold;
        }

        .grand-total td {
            background: #1d4ed8;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            background: #f3f4f6;
            color: #111827;
            font-size: 11px;
        }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
            color: #6b7280;
            font-size: 11px;
            text-align: center;
        }

        .signature-box {
            margin-top: 45px;
        }

        .signature-line {
            border-top: 1px solid #111827;
            width: 180px;
            text-align: center;
            padding-top: 6px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">

        <table class="header-table">
            <tr>
                <td style="width: 50%;">
                    <div class="company-name">TechStore</div>
                    <div>Karachi, Pakistan</div>
                    <div>Phone: +92 300 0000000</div>
                    <div>Email: info@techstore.com</div>
                    <div>Website: www.techstore.com</div>
                </td>

                <td style="width: 50%;">
                    <div class="invoice-title">TAX INVOICE</div>
                    <div class="invoice-no">
                        Invoice No: {{ $order->sale->invoice_no }}
                    </div>
                    <div class="invoice-no">
                        Order No: {{ $order->order_no ?? $order->id }}
                    </div>
                    <div class="invoice-no">
                        Date: {{ $order->sale->created_at->format('d F Y') }}
                    </div>
                </td>
            </tr>
        </table>

        <table class="info-table" style="margin-top: 25px;">
            <tr>
                <td style="width: 50%; padding-right: 8px;">
                    <div class="section-title">Bill To</div>
                    <div class="info-box">
                        <strong>Name:</strong>
                        {{ $order->sale->customer->user->name ?? ($order->sale->customer->name ?? 'Customer') }}
                        <br>

                        <strong>Email:</strong>
                        {{ $order->sale->customer->user->email ?? ($order->sale->customer->email ?? 'N/A') }}
                        <br>

                        <strong>Phone:</strong>
                        {{ $order->sale->customer->phone ?? 'N/A' }}
                        <br>

                        <strong>City:</strong>
                        {{ $order->city ?? 'N/A' }}
                        <br>

                        <strong>Address:</strong>
                        {{ $order->address ?? 'N/A' }}
                    </div>
                </td>

                <td style="width: 50%; padding-left: 8px;">
                    <div class="section-title">Invoice Details</div>
                    <div class="info-box">
                        <strong>Payment Method:</strong>
                        {{ ucfirst(str_replace('_', ' ', $order->sale->payment_method ?? 'N/A')) }}
                        <br>

                        <strong>Payment Status:</strong>
                        <span class="badge">
                            {{ ucfirst($order->sale->payment_status ?? 'Pending') }}
                        </span>
                        <br>

                        <strong>Order Status:</strong>
                        {{ ucfirst(str_replace('_', ' ', $order->order_status ?? 'Pending')) }}
                        <br>

                        <strong>Tracking No:</strong>
                        {{ $order->shipment->tracking_number ?? 'Not assigned' }}
                        <br>

                        <strong>Shipping Method:</strong>
                        {{ $order->shipment->shippingMethod->name ?? 'N/A' }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">Items</div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 35%;">Product</th>
                    <th style="width: 15%;">SKU</th>
                    <th style="width: 10%;" class="text-center">Qty</th>
                    <th style="width: 17%;" class="text-right">Unit Price</th>
                    <th style="width: 18%;" class="text-right">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($order->sale->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $item->product->name ?? 'Deleted Product' }}
                        </td>

                        <td>
                            {{ $item->product->sku ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $item->quantity }}
                        </td>

                        <td class="text-right">
                            PKR {{ number_format($item->unit_price, 2) }}
                        </td>

                        <td class="text-right">
                            PKR {{ number_format($item->total_price, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-wrapper">
            <table class="summary-table">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="text-right">
                        PKR {{ number_format($order->sale->subtotal, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Discount</td>
                    <td class="text-right">
                        PKR {{ number_format($order->sale->discount, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Tax</td>
                    <td class="text-right">
                        PKR {{ number_format($order->sale->tax, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Shipping</td>
                    <td class="text-right">
                        PKR {{ number_format($order->sale->shipping_cost, 2) }}
                    </td>
                </tr>

                <tr class="grand-total">
                    <td>Grand Total</td>
                    <td class="text-right">
                        PKR {{ number_format($order->sale->total_amount, 2) }}
                    </td>
                </tr>
            </table>
        </div>


        <div class="footer">
            Thank you for shopping with TechStore.
            <br>
            This invoice was generated automatically and does not require a physical signature.
            <br>
            Goods once sold cannot be returned without invoice.
        </div>

    </div>
</body>

</html>
