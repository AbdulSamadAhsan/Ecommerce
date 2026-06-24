<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ProductData;
use Maatwebsite\Excel\Facades\Excel;

class ProductReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
         return Excel::download(
            new ProductData(),
            'product-report.xlsx'
        );
    }
}