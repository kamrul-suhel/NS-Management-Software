<?php

namespace App\Http\Controllers\Bank;

use App\AccountTransaction;
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
    public function index(Request $request)
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
        $transactions = AccountTransaction::orderBy('id', 'DESC')
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
        //
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
     * Remove the specified resource from storage.
     *
     * @param  \App\AccountTransaction  $accountTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountTransaction $accountTransaction)
    {
        //
    }
}
