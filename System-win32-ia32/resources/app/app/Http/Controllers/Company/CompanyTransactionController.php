<?php

namespace App\Http\Controllers\Company;

use App\Company;
use App\CompanyTransaction;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyTransactionController extends Controller
{
    use ApiResponser;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //
        if($request->ajax()){
            $transactions = CompanyTransaction::with('company')->get();
            $companies = Company::select(['id', 'name'])->get();
            $data = [
                'companies' => $companies,
                'transactions' => $transactions
            ];
            return $this->successResponse($data, 200);
        }

        return view('welcome');
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
        $data = $request->all();
        $string ='';
        while(true){
            $string = $this->generateRandomString();
            $exists = Company::where('reference_number', $string)->first();
            echo $exists;
            if(!$exists){
                break;
            }
            continue;
        }
        $data['reference'] = $string;
        $companyTransaction = CompanyTransaction::create($data)->id;
        if($companyTransaction){
            $newCompanyTransaction = CompanyTransaction::with('company')
                ->where('id', $companyTransaction)->first();
            return $this->successResponse($newCompanyTransaction, 200);
        }
        dd($request->all());
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


    public function selectedCompany($id){
        $company_id = $id;
        $company_transaction = CompanyTransaction::where('company_id', $company_id)
            ->orderBy('id', 'DESC')
            ->first();
        if($company_transaction){
            return $this->successResponse($company_transaction, 200);
        }
    }

    private function generateRandomString($length = 11)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;

    }
}
