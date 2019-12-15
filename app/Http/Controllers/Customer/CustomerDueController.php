<?php

namespace App\Http\Controllers\Customer;

use App\Customer;
use App\CustomerLedger;
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
        if ($request->customer_id) {
            $customerId = $request->customer_id;

            $customer_transactions = Customer::where('id', $customerId)
                ->with(['transitions' => function ($query) {
                    $query->where('payment_due', '>', 0);
                }])
                ->get()
                ->pluck('transitions')
                ->collapse();

            $transactions = [];
            if ($customer_transactions->count() > 0) {
                $transactions = $customer_transactions;
            }

            $customerLedger = CustomerLedger::where('customer_id', $customerId)
                ->orderBy('created_at', 'DESC')
                ->first();
            $balance = 0;
            if ($customerLedger) {
                $balance = $customerLedger->balance;
            }

            $data['balance'] = $balance;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = Customer::findOrfail($request->customer_id);
        $customerLedger = new CustomerLedger();

        $debit = $request->debit;
        $credit = $request->credit;

        // Reduce amount from transition
        $transitions = Transaction::where('customer_id', $customer->id)
            ->where('payment_due','>', 0)
            ->get();

        if($transitions){
            foreach($transitions as $transition ){
                $paid = $transition->payment_due - $credit;

                if($paid <= 0){
                    $transition->payment_due = 0;
                    $transition->save();
                    $paid = abs($paid);
                }else{
                    $transition->payment_due = $paid;
                    $transition->save();
                    $paid = abs($paid);
                }
            }
        }

        $prevBalance = 0;
        // Get last balance for customer
        $balance = CustomerLedger::where('customer_id', $customer->id)
            ->orderBy('created_at', 'DESC')
            ->first();
        if($balance){
            $prevBalance = $balance->balance;
        }

        $balance = ($prevBalance + $debit) - $credit;

        $customerLedger->customer_id = $customer->id;
        $customerLedger->particular = $request->particular;
        $customerLedger->remark = $request->remark;
        $customerLedger->reference = $request->reference;
        $customerLedger->debit = $debit;
        $customerLedger->credit = $credit;
        $customerLedger->balance = $balance;

        $customerLedger->save();

        return response()->json($customer);

        if ($request->transactions) {
            $transactions = json_decode($request->transactions);
            foreach ($transactions as $transaction) {
                $currTransaction = Transaction::find($transaction->id);
                $payment_due = $currTransaction->payment_due - $transaction->newamount;
                $payment_paid = $currTransaction->paid + $transaction->newamount;

                $customer->duePayments()->create([
                    'transaction_id' => $transaction->id,
                    'paid' => $transaction->newamount,
                    'due' => $payment_due
                ]);

                $currTransaction->update([
                    'payment_due' => $payment_due,
                    'paid' => $payment_paid
                ]);
            }
        } else {
            $customer_due = $customer->duePayments()->create([
                'paid' => $request->paid,
                'due' => $request->due
            ]);
        }



        if ($customer_due) {
            return $this->successResponse($customer_due, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function customerLastDueAmount(Request $request)
    {
        $duPayment = Customer::where('id', $request->customer_id)
            ->with(['duePayments' => function ($query) {
                $query->orderBy('id', 'Desc')->first();
            }])->get()
            ->pluck('duePayments')
            ->collapse()
            ->first();

        if ($duPayment) {
            return [
                'previousDue' => $duPayment->due,
                'transaction_id' => $duPayment->transaction_id
            ];
        }

        return 0;
    }
}
