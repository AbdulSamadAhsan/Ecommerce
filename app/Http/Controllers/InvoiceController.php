<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Order;
class InvoiceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request,Order $order)
    {
        $order->load([
        'sale.customer',
        'sale.items.product',
        'shipment.shippingMethod',
        'invoice',
    ]);

       $pdf = Pdf::loadView('pdf.invoice', compact('order'));
   return $pdf->download(
    'Invoice-' .
    $order->sale->invoice_no .
    '-' .
    now()->format('Y-m-d-H-i-s') .
    '.pdf'
);
    }
}