<?php

namespace App\Http\Controllers\Company;

use App\CompanyProduct;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CompanyProductController extends Controller
{
    public function list($companyId)
    {
        $products = CompanyProduct::select(
            'company_product.*',
            DB::raw("CONCAT(products.name,' - ', products.description) as name"),
            'products.quantity',
            'products.is_barcode'
        )
            ->leftJoin('products', 'products.id', '=', 'company_product.product_id')
            ->where('company_product.company_id', $companyId)
            ->whereNotNull('products.name')
            ->where('products.quantity', '>', 0)
            ->groupBy('company_product.product_id')
            ->get();

        return response()->json([
            'products' => $products
        ]);
    }


    public function single($productId){
        $product = Product::with(['serials' => function($serial){
            $serial->where('is_sold', 0);
        }])
            ->where('id', $productId)
            ->first();

        return response()->json($product);
    }
}
