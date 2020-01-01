<?php

namespace App\Http\Controllers\Customer;

use App\Customer;
use App\CustomerLedger;
use App\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

    public function all($storeId){
        $transitions = CustomerLedger::select(
            'customer_ledgers.*',
            'customers.name',
            'customers.mobile'
        )
            ->leftJoin('customers', 'customers.id', '=', 'customer_ledgers.customer_id')
            ->whereIn('customer_ledgers.id', function($query){
                $query->select(DB::raw('max(id)'))
                    ->from('customer_ledgers')
                    ->groupBy('customer_ledgers.customer_id')
                    ->get();
            })
            ->orderBy('customer_ledgers.id', 'DESC')
            ->get();

        $setting = Store::find($storeId);
        return response()->json([
            'transitions' => $transitions,
            'setting' => $setting,
            'balance' => $transitions->sum('balance')
        ]);
    }
}
