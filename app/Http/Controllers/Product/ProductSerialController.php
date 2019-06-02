<?php

namespace App\Http\Controllers\Product;

use App\ProductSerial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductSerialController extends Controller
{
    //

    public function destroy($id){
        $productSerial = ProductSerial::findOrFail($id);
        $productSerial->delete();
    }
}
