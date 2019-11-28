<?php

namespace App\Http\Controllers\Company;

use App\Account;
use App\Company;
use App\CompanyTransaction;
use App\Traits\ApiResponser;
use BankAccount;
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
            $accounts = Account::all();

            // All Transitions
            $allTransition = [];
            $companyBalance = 0;
            if($request->has('companyId')){
                $companyId = $request->companyId;
                $allTransition = CompanyTransaction::where('company_id', $companyId)
                    ->get();

                $lastTransaction = CompanyTransaction::where('company_id', $companyId)
                    ->orderBy('created_at', 'DESC')
                    ->first();
                $companyBalance = $lastTransaction->balance;
            }

            $data = [
                'companies' => $companies,
                'transactions' => $transactions,
                'accounts' => $accounts,
                'allTransition' => $allTransition,
                'balance' => $companyBalance
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $companyTransaction = CompanyTransaction::where('company_id', $id)
            ->get();
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
        $companyTransition = CompanyTransaction::findOrFail($id);
        $companyTransition->manuel_date = $request->manuel_date;
        $companyTransition->payment_type = $request->payment_type;
        $companyTransition->remarkS = $request->remarks;
        $companyTransition->save();

        return response()->json(['success' => true]);
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
        $company = CompanyTransaction::findOrFail($id);

        $company->delete();
        return $this->successResponse($company);
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
