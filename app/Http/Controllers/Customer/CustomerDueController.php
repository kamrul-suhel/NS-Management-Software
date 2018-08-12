<?php

namespace App\Http\Controllers\Customer;

use App\Customer;
use App\Traits\ApiResponser;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerDueController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if($request->customer_id){
            $customer_transactions = Customer::where('id', $request->customer_id)
                ->with(['transitions' => function($query){
                    $query->where('payment_due', '>', 0);
                }])
                ->get()
                ->pluck('transitions')
                ->collapse();

            $transactions = [];
            if($customer_transactions->count() > 0){
                $transactions = $customer_transactions;
            }

            $data['previous_record'] = $this->customerLastDueAmount($request);
            $data['transactions'] = $transactions;
            return $this->successResponse($data, 200);
        }

        return view('welcome');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if($request->transactions){
            $transactions = json_decode($request->transactions);
            foreach($transactions as $transaction){
                $currTransaction = Transaction::find($transaction->id);
                $payment_due = $currTransaction->payment_due - $transaction->newamount;
                $currTransaction->update(['payment_due' => $payment_due]);
            }
        }

        $customer = Customer::findOrfail($request->customer_id);
        $customer_due = $customer->duePayments()->create([
           'paid'   => $request->paid,
           'due'    => $request->due
        ]);

        if($customer_due){
            return $this->successResponse($customer_due, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function customerLastDueAmount(Request $request){
        $duPayment = Customer::where('id', $request->customer_id)
            ->with(['duePayments' => function($query){
                $query->orderBy('id', 'Desc')->first();
            }])->get()
            ->pluck('duePayments')
            ->collapse()
            ->first();

        if($duPayment){
            return [
                'previousDue' => $duPayment->due,
                'transaction_id' => $duPayment->transaction_id
                ];
        }

        return 0;
    }
}
