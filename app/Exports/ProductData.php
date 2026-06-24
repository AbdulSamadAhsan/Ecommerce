<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ProductData implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
     public function collection()
    {
             return Product::with(['supplier', 'category'])
        ->get()
        ->map(function ($product) {
            return [
                'id'             => $product->id,
                'name'           => $product->name,
                'sku'            => $product->sku,
                'supplier_name'  => $product->supplier?->user->name,
                'category_name'  => $product->category?->name,
                'brand_name'     => $product->brand->title,
                'purchase_price' => $product->purchase_price,
                'selling_price'  => $product->selling_price,
                "per_unit"       => $product->profit,
                'quantity'       => $product->quantity,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Product Name',
            'SKU',
            "Supplier Name",
            "Category ",
            "Brand",
            'Purchase Price',
            'Selling Price',
            "Profit Per Unit",
            'Stock Quantity',
        ];
    }
}