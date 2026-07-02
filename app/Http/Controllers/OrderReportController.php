<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
class OrderReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request,$id)
    { 
      $order= Order::findOrFail($id);
    
    $pdf = Pdf::loadView('pdf.orderInvoice', compact('order'));
      return $pdf->download("Order Invoice".date("d-F-Y").".pdf");
    }
}