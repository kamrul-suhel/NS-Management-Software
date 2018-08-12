<?php

namespace App\Http\Controllers\Customer;

use App\Customer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerTransitionController extends ApiController
{
    /**
     * @param Customer $buyer
     * @return mixed
     */
    public function index(Customer $customer)
    {
        $transactions = $customer->transitions()
            ->with('product')
        ->get()
        ->pluck('product')
        ->unique()
        ->values()
        ->sortByDesc('created_at');
        return $transactions;
        $total_transition = $transactions->count();
        $last_transition = $transactions->sortBy('id')->last();
//        dd($last_transition);
        return $this->showAll($transactions);
    }
}
