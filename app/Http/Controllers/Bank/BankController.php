<?php

namespace App\Http\Controllers\Bank;

use App\AccountTransaction;
use App\Bank;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BankController extends ApiController
{
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
        //
        $banks = Bank::all();

        // Get all transition for all accounts
        $balance = AccountTransaction::select('amount')
            ->whereIn('payment_type', [2,3,5]);

        $withdraw = AccountTransaction::select('amount')
            ->whereIn('payment_type', [1,4]);

        $balance = $balance->get()->sum('amount');
        $withdraw = $withdraw->get()->sum('amount');
        $balance = $balance - $withdraw;
        $result = [
            'banks' => $banks,
            'balance' => $balance,
            'withdraw' => $withdraw
        ];

        return response()->json($result);
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
        $bank = new Bank();
        $bank->name = $request->name;
        $bank->address = $request->address;
        $bank->phone = $request->phone;
        $bank->save();

        return response()->json($bank);
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
        $bank = Bank::findOrFail($id);
        $request->has('name') ? $bank->name = $request->name : null;
        $request->has('address') ? $bank->address = $request->address : null;
        $request->has('phone') ? $bank->phone = $request->phone : null;

        $bank->save();

        return response()->json($bank);
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
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return response()->json($bank);
    }
}
