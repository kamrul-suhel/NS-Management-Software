<?php

namespace App\Http\Controllers\Product;

use App\MissingProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductMissingListController extends Controller
{
    public function list(){
        $productList = MissingProduct::select(
            'missing_product.*',
            'products.name as product_name',
            'product_serials.color',
            'product_serials.imei',
            'product_serials.barcode',
            'product_serials.product_warranty'
        )
            ->leftJoin('products', 'products.id', '=', 'missing_product.product_id')
            ->leftJoin('product_serials', 'product_serials.id', '=', 'missing_product.product_serial_id')
        ->get();

        return response()->json([
            'products' => $productList
        ]);
    }
}
