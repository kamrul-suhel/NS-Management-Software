<?php

namespace App\Http\Controllers\Customer;

use App\Customer;
use App\Traits\ApiResponser;
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
        if($request->customer_id){
            $previous_due = $this->customerLastDueAmount($request);
            $data = [
              'previousDue' => $previous_due
            ];
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $customer = Customer::findOrfail($request->customer_id);
        $customer_due = $customer->duePayments()->create([
           'paid'   => $request->paid,
           'due'    => $request->due
        ]);

        if($customer_due){
            return $this->successResponse($customer_due, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    }

    public function customerLastDueAmount(Request $request){
        $duPayment = Customer::where('id', $request->customer_id)
            ->with(['duePayments' => function($query){
                $query->orderBy('id', 'Desc')->first();
            }])->get()
            ->pluck('duePayments')
            ->collapse()
            ->first();

        if($duPayment){
            return $duPayment->due;
        }

        return 0;
    }
}
