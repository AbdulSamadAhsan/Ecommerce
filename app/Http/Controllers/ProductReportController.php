<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ProductData;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;

class ProductReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

          $this->authorize('viewAny', Product::class);
         return Excel::download(
            new ProductData(),
            'product-report.xlsx'
        );
    }
}