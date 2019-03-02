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
        $transactionId = 0;
        $transaction = DB::transaction(function () use ($request, $customer, &$transactionId) {
            $attach_product = [];
            $unique_id = $this->getUniqueId();

            $type = $request->payment_status === '1' ? 'paid' : 'due-paid';

            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'seller_id' => $request->seller_id,
                'store_id' => $request->store_id,
                'invoice_number' => $unique_id,
                'discount_amount' => $request->discount,
                'special_discount' => $request->special_discount,
                'total' => $request->total + $request->service_charge,
                'payment_status' => $request->payment_status,
                'type' => $type,
                'payment_due' => $request->payment_due ? $request->payment_due : 0,
                'paid' => $request->paid,
				'service_charge' => $request->service_charge
            ]);

            $transactionId = $transaction->id;

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

            $products = json_decode($request->products, true);

            foreach ($products as $product) {
                // check if has selected serials
                // check if has selected serials
                if ($product['serials']) {
                    // Find the serials key
                    foreach ($product['serials'] as $serial) {
                        $serial = ProductSerial::findOrFail($serial['id']);
                        $serial->is_sold = 1;
                        $serial->transaction_id = $transaction->id;
                        $serial->update();
                    }
                }

                $attach_product[$product['id']] = [
                    'sale_quantity' => $product['quantity']
                ];

                $curProduct = Product::findOrFail($product['id']);
                $curProduct->quantity = $curProduct->quantity - $product['quantity'];

                $curProduct->save();
            }


            $transaction->products()->sync($attach_product);
            return $transaction;
        });

        $product = $transaction
            ->with('products.seller')
            ->with('customer')
            ->first();

        $product->transaction_id = $transactionId;
//
        return $this->showOne($product);
    }


}
