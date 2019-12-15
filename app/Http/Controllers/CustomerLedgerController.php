<?php

namespace App\Http\Controllers;

use App\CustomerLedger;
use Illuminate\Http\Request;

class CustomerLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $ledger = new CustomerLedger();
        $ledger->customer_id = $request->customer_id;
        $ledger->credit = $request->credit;
        $ledger->debit = $request->debit;
        $ledger->balance = $request->balance;
        $ledger->particular = $request->particular;
        $ledger->reference = $request->reference;
        $ledger->remark = $request->remark;
        $ledger->save();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerLedger  $customerLedger
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerLedger $customerLedger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerLedger  $customerLedger
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerLedger $customerLedger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerLedger  $customerLedger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerLedger $customerLedger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerLedger  $customerLedger
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerLedger $customerLedger)
    {
        //
    }
}
