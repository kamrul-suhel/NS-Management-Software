<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Store;
use App\Traits\ApiResponser;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class TransactionController extends ApiController
{
    use ApiResponser;
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $transactions = Transaction::with(['products', 'serials', 'seller'])
            ->with('customer')
            ->where('store_id', $request->shopId)
            ->orderBy('id', 'DESC')
            ->get();

        $todayTransaction = Transaction::where('created_at', '>', Carbon::now()->startOfDay())
            ->where('created_at', '<', Carbon::now()->endOfDay())
            ->get()
            ->count();

        $transactions = $transactions->each(function ($transaction) {
            foreach ($transaction->products as $product) {
                $product->sale_quantity = $product->pivot->sale_quantity;
            }
        });
        $total = $transactions->count();

        $amount_transactions = $transactions->sum('total');

        $payment_type = Transaction::getPaymentStatusType();

        $collect = collect([
            'transactions' => $transactions,
            'total_tk' => $amount_transactions,
            'total_transactions' => $total,
            'today_total_transaction' => $todayTransaction,
            'payment_type' => $payment_type
        ]);

        return $this->showAll($collect);
    }


    public function generateRandomString($length = 11)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('welcome');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
        return $this->showOne($transaction);
    }

    public function showPrint(Request $request, int $id)
    {
        if ($request->ajax()) {
            $transaction = Transaction::with(['products.serials','serials', 'customer','seller'])
                ->with('products')
                ->where('id', '=', $id)
                ->first();
            foreach ($transaction->products as $product) {

            	$serials = $transaction->serials;
				$productSaleSerial = [];
				$warranty = '';
            	if($serials->count() > 0){
            		foreach($serials as $serial){
            			$isSerial = false;
            			$warranty = $serial->product_warranty;
            			foreach($product->serials as $productSerial){
            				if($serial->product_id === $productSerial->product_id){
								$isSerial = true;
							}
						}
						if($isSerial){
							$productSaleSerial[] = $serial;
						}
					}
				}
				$product->productWarranty = $warranty;
				$product->productSaleSerial = $productSaleSerial;
                $product->sale_quantity = $product->pivot->sale_quantity;
                $product->sale_feet = $product->pivot->sale_feet;
                $product->discount_percentage = $product->pivot->discount_percentage;
            }
            $setting = Store::find($transaction->store_id);

            $data = collect([
                'transaction' => $transaction,
                'setting' => $setting
            ]);
            return $data;
        }

        return view('welcome');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        //
        if ($request->ajax()) {
            $transaction = Transaction::with('products.seller')
                ->with('customer')
                ->findOrFail($request->id);
            return $this->showOne($transaction);
        }

        return view('welcome');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $transaction = Transaction::find($request->id);
        $transaction->products()->detach();
        if($transaction->delete()){
            return $this->successResponse($transaction, 200);
        }
    }
}
