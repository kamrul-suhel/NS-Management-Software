<?php

namespace App\Http\Controllers\Company;

use App\Companyreturn;
use App\Product;
use App\ProductSerial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyReturnStoreController extends Controller
{
    //
    public function store(Request $request){
        $product = Product::findOrFail($request->product_id);
        $quantity = $product->quantity - $request->quantity;
        $product->quantity = $quantity;
        $product->status = $quantity <= 0 ? Product::UNAVAILABLE_PRODUCT : Product::ABAILABLE_PRODUCT;
        $product->save();

        // Add record into company return
        if($request->is_barcode === 'no'){
            $companyReturn = new Companyreturn();
            $companyReturn->product_id = $request->product_id;
            $companyReturn->product_serial_id = null;
            $companyReturn->note = $request->note;
            $companyReturn->company_id = $request->company_id;
            $companyReturn->quantity = 1;
            $companyReturn->save();
        }else{
            foreach($request->serials as $serial){
                $companyReturn = new Companyreturn();
                $companyReturn->product_id = $request->product_id;
                $companyReturn->product_serial_id = $serial;
                $companyReturn->note = $request->note;
                $companyReturn->company_id = $request->company_id;
                $companyReturn->quantity = 1;
                $companyReturn->save();

                $serial = ProductSerial::findOrFail($serial);
                $serial->is_sold = 2;
                $serial->save();
            }
        }

        return response()->json(
            [
                'success' => true
            ]
        );
    }
}