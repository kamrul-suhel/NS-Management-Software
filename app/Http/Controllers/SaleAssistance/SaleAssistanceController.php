<?php

namespace App\Http\Controllers\SaleAssistance;

use App\Product;
use App\ProductSerial;
use App\SaleAssistant;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SaleAssistanceController extends Controller
{
    use ApiResponser;

    //

    public function index()
    {

    }

    public function getScannedProduct(Request $request)
    {

        $code = $request->code;

        $productSerial = Product::select(
            'products.*',
            'product_serials.id as product_serial_id',
            'product_serials.barcode',
            'product_serials.product_warranty',
            'product_serials.color',
            'product_serials.is_sold'
        )
            ->leftJoin('product_serials', 'products.id', '=', 'product_serials.product_id')
            ->where('barcode', 'LIKE', "%" . $code . "%")
            ->first();

        return response()->json($productSerial);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSaleAssistant(Request $request)
    {
        $userId = $request->userId;
        $serials = $request->serial;

        if (count($serials) > 0) {
            foreach ($serials as $serial) {
                $saleAssistant = new SaleAssistant();
                $saleAssistant->user_id = $userId;
                $saleAssistant->product_serial_id = $serial;
                $saleAssistant->save();
            }
        }

        return response()->json(['success' => true]);
    }


    public function getSaleAssistantProducts(Request $request)
    {
        $date = $request->date;
        $userId = $request->userId;

        $productSerial = Product::select(
            'products.*',
            'product_serials.*',
            'product_serials.barcode',
            'product_serials.product_warranty',
            'product_serials.color',
            'product_serials.is_sold',
            'sale_assistants.user_id as sale_user_id',
            'sale_assistants.product_serial_id as sale_serial_id',
            'sale_assistants.user_id as sale_user_id',
            'sale_assistants.status as sale_status'
        )
            ->leftJoin('product_serials', 'products.id', '=', 'product_serials.product_id')
            ->leftJoin('sale_assistants', 'product_serials.id', '=', 'sale_assistants.product_serial_id')
            ->where('sale_assistants.user_id', $userId);

        if ($request->date != 'null') {
            $productSerial = $productSerial->whereDate('sale_assistants.created_at', $date);
        }

        $productSerial = $productSerial->get();

        return response()->json($productSerial);
    }
}
