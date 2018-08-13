<?php

namespace App\Http\Controllers\Customer;

use App\Customer;
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
        $transactions = Customer::where('id', $customer->id)
            ->with('transitions.products')
            ->orderBy('id', 'desc')
            ->get()
            ->pluck('transitions')
            ->collapse();
            $total_transition = $transactions->count();
//        $last_transition = $transactions->sortBy('id')->last();

        $data = [
            'total_transactions' => $total_transition,
            'transactions' => $transactions
        ];
        return $this->successResponse($data, 200);
    }
}
