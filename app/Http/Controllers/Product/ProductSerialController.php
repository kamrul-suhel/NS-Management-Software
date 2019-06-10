<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\ProductSerial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductSerialController extends Controller
{
    //

    public function destroy(Request $request, $id){
        $productSerial = ProductSerial::findOrFail($id);
        // If type='quantity' then remove quantity form product
        if($request->type === 'quantity'){
           $product = Product::findOrFail($productSerial->product_id);
           $product->quantity = $product->quantity - 1; // Reduce one product
            $product->save();
        }

        $productSerial->delete();
    }
}
