<?php

namespace App\Http\Controllers\Product;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Customer\CustomerDueController;
use App\Product;
use App\ProductSerial;
use App\Seller;
use App\Transaction;
use App\User;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Customer $customer)
    {
        $transaction = DB::transaction(function() use ($request, $customer){
            $attach_product =[];
            $unique_id = $this->getUniqueId();

            $transaction = Transaction::create([
                'customer_id'      => $customer->id,
                'invoice_number'         => $unique_id,
                'discount_amount'          => $request->discount,
                'total'          => $request->total,
                'payment_status'=> $request->payment_status,
                'payment_due'   => $request->payment_due ? $request->payment_due : 0,
                'paid'         => $request->paid
            ]);

            if($request->payment_due && $request->payment_due > 0){
                $request['customer_id'] = $customer->id;
                $customerDueController = new CustomerDueController();
                $previousDue = $customerDueController->customerLastDueAmount($request);

                $customer->duePayments()->create([
                   'paid'   => 0,
                   'due'    => $request->payment_due + $previousDue
                ]);
            }

            $products = json_decode($request->products);


            foreach($products as $product){
                // check if has selected serials
                if($product->selectedSerials){
                    // Find the serials key
                    $serials = ProductSerial::where('product_id', $product->product->id)
                        ->whereIn('product_serial',$product->selectedSerials)->get();
                    foreach($serials as $serial){
                        $serial->is_sold = 1;
                        $serial->transaction_id = $transaction->id;
                        $serial->update();
                    }
                }

                $cur_product= Product::find($product->product->id);
                $selected_quantity = $product->selected_quantity;
                $cur_product->quantity -= $selected_quantity;

                $attach_product[$product->product->id] = ['sale_quantity' => $product->selected_quantity];

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
