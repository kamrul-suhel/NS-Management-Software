<?php

namespace App\Http\Controllers\Bank;

use App\Account;
use App\AccountTransaction;
use App\Bank;
use App\Company;
use App\CompanyTransaction;
use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;

class AccountTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Bank $bank, Account $account)
    {
        if($request->has('page') && $request->page == 'account_transaction'){
            if($request->payment_type == 'company'){
                $companies = Company::select(['id', 'name'])->get();
                return response()->json($companies);
            }

            if($request->payment_type === 'transaction'){
                $transactions = Transaction::select('id', 'invoice_number')->get();
                return response()->json($transactions);
            }
        }
        $transactions = AccountTransaction::where('account_id', $account->id)
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json($transactions);
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
        $accountTransaction = new AccountTransaction();
        $accountTransaction->amount = $request->amount;
        $accountTransaction->account_id = $request->account_id;

        switch($request->payment_type){
            case 'Transaction':
                $request->has('transaction_id') ? $accountTransaction->transaction_id = $request->transaction_id : null;
                break;

            case 'Company':
                $request->has('company_id') ? $accountTransaction->company_id = $request->company_id : null;
                break;
        }

        $request->has('reference') ? $accountTransaction->reference = $request->reference : null;
        $accountTransaction->payment_type = $request->payment_type;
        $accountTransaction->save();

        return response()->json($accountTransaction);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AccountTransaction  $accountTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(AccountTransaction $accountTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AccountTransaction  $accountTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountTransaction $accountTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AccountTransaction  $accountTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountTransaction $accountTransaction)
    {
        //
    }

    /**
     * @param Bank $bank
     * @param Account $account
     * @param AccountTransaction $accountTransaction
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Bank $bank, Account $account, AccountTransaction $accountTransaction)
    {
        $accountTransaction->delete();
        return response()->json($accountTransaction);
    }
}
