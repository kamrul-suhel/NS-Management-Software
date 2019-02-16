<?php

namespace App\Http\Controllers\Company;

use App\Company;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
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

        if($request->ajax()){
			$companies = Company::with('transactions')->orderBy('id', 'DESC')->get();
			$companiesTransaction = Company::with('transactions')
				->get()
				->pluck('transactions')
				->collapse()
				->each(function($product){
					return $product->sum('balance');
				});

        	$data = [
        		'companies' => $companies,
				'totalCompany' => $companies->count(),
				'companyTransaction'	=> $companiesTransaction
			];
            return $this->successResponse($data, 200);
        }

        return view('welcome');
    }

    public function productCompany(Request $request){
        $companies = Company::select('id', 'name')->orderBy('name', 'ASC')->get();
        return $this->successResponse($companies, 200);
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
        $data['reference_number'] = $string;

        $company = Company::create($data);
        if($company){
            return $this->successResponse($company, 200);
        }
    }

    public function generateRandomString($length = 11)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;

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
        $company = Company::findOrfail($id);
        return $this->successResponse($company, 200);
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
        $exCompany = Company::findOrfail($id);
        $requestCompany = $request->all();
        $save = $exCompany->update($requestCompany);
        if($save){
            return $this->successResponse($exCompany, 200);
        }

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
        $company = Company::findOrfail($id);
        $deleteCompany = $company->delete();
        if($deleteCompany){
            return $this->successResponse($company, 200);
        }

    }
}
