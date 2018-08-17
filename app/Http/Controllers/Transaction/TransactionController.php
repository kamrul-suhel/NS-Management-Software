<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Setting;
use App\Traits\ApiResponser;
use App\Transaction;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::with(['products', 'serials'])
            ->with('customer')
            ->orderBy('id', 'DESC')
            ->get();

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
            $transaction = Transaction::with('products')
                ->with('customer')
                ->where('id', '=', $id)
                ->first();

            foreach ($transaction->products as $product) {
                $product->sale_quantity = $product->pivot->sale_quantity;
            }
            $setting = Setting::find(1);

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
