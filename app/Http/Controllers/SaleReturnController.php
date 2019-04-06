<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductSerial;
use App\SaleReturn;
use App\SaleReturnLine;
use Illuminate\Http\Request;

class SaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
           'products' => 'required|array',
           'products.*.saleReturnQuantity' => 'required|integer'
        ]);

        if ($request->has('products')) {
            $saleReturn = new SaleReturn();
            $saleReturn->store_id = $request->store_id;
            $saleReturn->seller_id = $request->seller_id;
            $saleReturn->transaction_id = $request->transaction_id;
            $saleReturn->total = $request->total;
            $request->has('note') ? $saleReturn->note = $request->note : null;

            $saleReturn->save();

            if($saleReturn){
                $products = collect($request->products);
                $products->map(function ($product) use ($saleReturn) {
                    if (isset($product['saleReturnQuantity']) && $product['saleReturnQuantity'] > 0) {
                        // Create sale return line
                        $saleReturnLine = new SaleReturnLine();
                        $saleReturnLine->product_id = $product['product_id'];
                        $saleReturnLine->sale_return_id = $saleReturn->id;
                        isset($product['product_serial_id']) && $product['product_serial_id'] > 0 ? $saleReturnLine->product_serial_id = $product['product_serial_id'] : null;
                        $saleReturnLine->quantity = $product['saleReturnQuantity'];
                        $saleReturnLine->save();

                        if($saleReturnLine){
                            // Now roll back all product
                            $updateProduct = Product::findOrFail($product['product_id']);
                            $updateProduct->quantity = $updateProduct->quantity + $product['saleReturnQuantity'];
                            $updateProduct->save();

                            // Now rollback product serial if exists
                            if($product['product_serial_id'] > 0){
                                $productSerial = ProductSerial::find($product['product_serial_id']);
                                $productSerial->is_sold = 0;
                                $productSerial->save();
                            }
                        }
                    }
                });
            }

            return response()->json('success');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleReturn $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function show(SaleReturn $saleReturn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleReturn $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleReturn $saleReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\SaleReturn $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleReturn $saleReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaleReturn $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleReturn $saleReturn)
    {
        //
    }
}
