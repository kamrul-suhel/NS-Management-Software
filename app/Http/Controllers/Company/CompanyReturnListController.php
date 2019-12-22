<?php

namespace App\Http\Controllers\Company;

use App\Companyreturn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyReturnListController extends Controller
{
    public function list($companyId){
        $companyReturns = Companyreturn::select(
            'company_return.*',
            'products.name',
            'product_serials.barcode',
            'product_serials.imei',
            'product_serials.color',
            'product_serials.product_warranty'
        )
            ->leftJoin('products', 'products.id', '=', 'company_return.product_id')
            ->leftJoin('product_serials', 'product_serials.id', '=', 'company_return.product_serial_id')
            ->where('company_return.company_id',$companyId)
            ->get();

        return response()->json([
            'products' => $companyReturns
        ]);
    }
}
