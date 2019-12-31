<?php

namespace App\Http\Controllers\Customer;

use App\Customer;
use App\CustomerLedger;
use App\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerLedgerPrint extends Controller
{
    public function list($id, $storeId){
        $transitions = [];
        $total = 0;
        $debit = 0;
        $credit = 0;
        $balance = 0;
        $transitions = CustomerLedger::where('customer_id', $id)
            ->orderBy('id', 'DESC')
            ->get();

        $lastTransaction = $transitions->first();

        $setting = Store::find($storeId);
        $customer = Customer::find($id);

        return response()->json([
            'transitions' => $transitions,
            'lastTransition' => $lastTransaction,
            'total' => $total,
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $balance,
            'setting' => $setting,
            'customer' => $customer
        ]);
    }
}
