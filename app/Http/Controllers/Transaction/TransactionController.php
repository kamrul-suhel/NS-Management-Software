<?php

namespace App\Http\Controllers\Transaction;

use App\Bkash;
use App\Http\Controllers\ApiController;
use App\Product;
use App\ProductSerial;
use App\SaleReturn;
use App\Store;
use App\Traits\ApiResponser;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

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
        $perPage = $request->has('rowsPerPage') ? $request->rowsPerPage : 2;
        $shopId = $request->has('shopId') ? $request->shopId : null;

        $transactions = Transaction::with(['products', 'serials', 'seller', 'customer','bkash', 'accountTransaction.account'])
            ->where('store_id', $shopId)
            ->orderBy('created_at', 'DESC');

        $totalRows = 0;
        if($request->has('query_type') && $request->query_type === 'transactionPage'){
            $transactions = $transactions->paginate($perPage);
            $totalRows = $transactions->total();
        }else{
            $transactions = $transactions->get();
            $totalRows = $transactions->count();
        }

        $todayTransaction = Transaction::where('created_at', '>', Carbon::now()->startOfDay())
            ->where('created_at', '<', Carbon::now()->endOfDay())
            ->get()
            ->count();

        $transactions = $transactions->each(function ($transaction) {
            foreach ($transaction->products as $product) {
                $product->sale_quantity = $product->pivot->sale_quantity;
            }
        });
        $total = Transaction::select('id')
            ->where('store_id', $shopId)
            ->get()
            ->count();

        $amount_transactions = Transaction::select('total')
            ->where('store_id', $shopId)
            ->where('payment_status', '!=', '4')
            ->get()
            ->sum('total');

        // Get Sale return
        $saleReturn = SaleReturn::select('total_sale_price')
            ->get()
            ->sum('total_sale_price');
        

        $payment_type = Transaction::getPaymentStatusType();

        $collect = collect([
            'transactions' => $transactions,
            'total_tk' => $amount_transactions - $saleReturn,
            'total_transactions' => $total,
            'today_total_transaction' => $todayTransaction,
            'payment_type' => $payment_type,
            'total_rows' => $totalRows
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
            // For sale return.
            if($request->has('sale_return')){
                $transaction = Transaction::with(['products.soldSerials']);
                $transaction = $transaction->where('invoice_number', 'LIKE', '%'.$request->sale_return.'%');
            }else{
                $transaction = Transaction::with(['products','products.serials','serials', 'customer','seller']);
                $transaction = $transaction
                    ->where('id', '=', $id);
            }
            $transaction = $transaction->first();

            if($request->has('sale_return')){
                return response()->json($transaction);
            }

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

    public function searchByInvoice(Request $request){
        $transaction = Transaction::select([
            'transactions.id as transaction_id',
            'transactions.invoice_number',
            'transactions.total',
            'product_transaction.product_id',
            'product_transaction.sale_quantity',
            'products.name',
            'products.sale_price',
            'products.purchase_price',
            'product_serials.color',
            'product_serials.barcode',
            'product_serials.imei',
            'product_serials.id as product_serial_id'
        ])
            ->leftJoin('product_transaction', 'product_transaction.transaction_id', '=', 'transactions.id')
            ->leftJoin('products', 'products.id', '=', 'product_transaction.product_id')
            ->leftJoin('product_serials', 'product_serials.transaction_id', '=', 'product_transaction.transaction_id')
            ->where('transactions.invoice_number', 'LIKE', '%'.$request->invoice_number.'%')
            ->get();

        return response()->json($transaction);
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
        // change Bkash status
        if($request->has('bkash_status')){
            $bkash = Bkash::findOrFail($request->bkash_id);
            $bkash->status = $request->bkash_status;
            $bkash->save();
            return response()->json($bkash);
        }

        // Change transaction payment type
        if($request->has('payment_type')){
            $transaction->type = $request->payment_type;

            $transaction->payment_status = Transaction::getPaymentStatus($request->payment_type);

            $transaction->save();
        }

        // Change Transaction status and approved_by
        if($request->has('status')){
            $transaction->status = $request->status;
            $transaction->approved_by = $request->user_id;
            $transaction->save();
        }

        return response()->json($transaction);
    }

    /**
     * @param Request $request
     * @param Transaction $transaction
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        // First get product with transaction
        $transactions = Transaction::select([
            'transactions.id',
            'product_transaction.product_id',
            'product_transaction.sale_quantity',
            'products.name'

        ])
            ->leftJoin('product_transaction', 'transactions.id', '=', 'product_transaction.transaction_id')
            ->leftJoin('products','products.id', '=', 'product_transaction.product_id')
            ->where('transactions.id', $request->id)
            ->get();

//        $transactions->each(function($transaction){
//           $product =  Product::findOrfail($transaction->product_id);
//           $product->quantity = $product->quantity + $transaction->sale_quantity;
//           $product->save();
//        });

        // Role back serials
        $serials = Transaction::select([
            'transactions.id',
            'product_serials.id as serial_id'
        ])
            ->leftJoin('product_serials', 'transactions.id', '=', 'product_serials.transaction_id')
            ->where('transactions.id', $request->id)
            ->get();

        return response()->json($serials);

        $serials->each(function($serial){
            if($serial->serial_id === null){
                return;
            }
            $productSerial = ProductSerial::findOrFail($serial->serial_id);
            $productSerial->transaction_id = NULL;
            $productSerial->is_sold = 0;
            $productSerial->save();
        });

        $transaction = Transaction::findOrFail($request->id);
        $transaction->products()->detach();
        if($transaction->delete()){
            return $this->successResponse($transaction, 200);
        }
    }
}
