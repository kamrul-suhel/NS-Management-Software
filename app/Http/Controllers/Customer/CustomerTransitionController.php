<?php

namespace App\Http\Controllers\Customer;

use App\Customer;
use App\CustomerLedger;
use App\Http\Controllers\ApiController;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerTransitionController extends ApiController
{
    use ApiResponser;
    /**
     * @param Customer $buyer
     * @return mixed
     */
    public function index(Customer $customer)
    {
        $transactions = CustomerLedger::where('customer_id',$customer->id)
            ->orderBy('id', 'ASC')
            ->get();

            $totalTransition = $transactions->count();
            $debit = (int) $transactions->sum('debit');
            $credit = (int) $transactions->sum('credit');
            $balance = (int) ($credit - $debit);

        $data = [
            'credit' => number_format($credit, '2',',',','),
            'debit' => number_format($debit, '2', ',', ','),
            'balance'   => number_format($balance, '2', ',', ','),
            'total_transactions' => $totalTransition,
            'transactions' => $transactions
        ];
        return $this->successResponse($data, 200);
    }
}
