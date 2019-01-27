<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Customer\CustomerDueController;
use App\Product;
use App\ProductSerial;
use App\Transaction;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Customer $customer)
    {
        $transaction = DB::transaction(function () use ($request, $customer) {
            $attach_product = [];
            $unique_id = $this->getUniqueId();

            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'seller_id' => $request->seller_id,
                'store_id' => $request->store_id,
                'invoice_number' => $unique_id,
                'discount_amount' => $request->discount,
                'special_discount' => $request->special_discount,
                'total' => $request->total,
                'payment_status' => $request->payment_status,
                'payment_due' => $request->payment_due ? $request->payment_due : 0,
                'paid' => $request->paid,
				'service_charge' => $request->service_charge
            ]);


            if ($request->payment_due && $request->payment_due > 0) {
                $request['customer_id'] = $customer->id;
                $customerDueController = new CustomerDueController();
                $previousDue = 0;

                $previousTransaction = $customerDueController->customerLastDueAmount($request);
                if($previousTransaction['previousDue']){
                    $previousDue = $previousTransaction['previousDue'];
                }

                $transaction_id = $transaction->id;
                $due = $request->payment_due + $previousDue;

                $customer->duePayments()->create([
                    'transaction_id' => $transaction_id,
                    'paid' => 0,
                    'due' => $due
                ]);
            }

            $products = json_decode($request->products);

            foreach ($products as $product) {
                // check if has selected serials
                if ($product->selectedSerials) {
                    // Find the serials key
                    $serials = ProductSerial::where('product_id', $product->product->id)
                        ->whereIn('product_serial', $product->selectedSerials)->get();
                    foreach ($serials as $serial) {
                        $serial->is_sold = 1;
                        $serial->transaction_id = $transaction->id;
                        $serial->update();
                    }
                }

                $cur_product = Product::find($product->product->id);
                $sale_quantity = 0;
                $sale_feet = 0;

                // check what type of product
                if($cur_product->quantity_type === 'feet'){
                    $totalFeet = ($cur_product->quantity * $cur_product->quantity_per_feet) + $cur_product->feet;
                    $remainingFeet = $totalFeet - $product->selected_quantity;

                    $remainingQuantity = $remainingFeet / $cur_product->quantity_per_feet;

                    $cur_product->quantity = floor($remainingQuantity);
                    $cur_product->feet = $remainingFeet % $cur_product->quantity_per_feet;

                    // now get the sale quantity.
                    $sale_quantity = floor($product->selected_quantity / $cur_product->quantity_per_feet);
                    $sale_feet = $product->selected_quantity % $cur_product->quantity_per_feet;
                }else{
                    $selected_quantity = $product->selected_quantity;
                    $cur_product->quantity -= $selected_quantity;
                    $sale_quantity = $product->selected_quantity;
                }

                $attach_product[$product->product->id] = [
                    'sale_quantity' => $sale_quantity,
                    'sale_feet' => $sale_feet,
                    'discount_percentage' => $product->selected_percentage
                ];

                $cur_product->save();
            }

            $transaction->products()->sync($attach_product);
            return $transaction;
        });

        $product = $transaction
            ->with('products.seller')
            ->with('customer')
            ->first();

        return $this->showOne($product);
    }


}
