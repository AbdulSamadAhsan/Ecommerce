<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
   $orders= Order::with(['customer.user', 'shipment', 'sale'])->latest()->limit(1500)->get();

    $pdf = Pdf::loadView('pdf.orderReport', compact('orders'));
      return $pdf->download("Order Report".date("d-F-Y").".pdf");
    }
}