<?php

namespace App\Http\Controllers\Bank;

use App\Account;
use App\AccountTransaction;
use App\Bank;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Bank $bank)
    {
        //
        $accounts = Account::where('bank_id', $bank->id)
            ->orderBy('id', 'desc')
            ->get();

        // Get all transition for all accounts
        $balance = AccountTransaction::select('amount')
            ->leftJoin('accounts', 'account_transactions.account_id', '=', 'accounts.id')
            ->where('accounts.bank_id', $bank->id)
            ->whereIn('payment_type', [2,3,5]);

        $withdraw = AccountTransaction::select('amount')
            ->leftJoin('accounts', 'account_transactions.account_id', '=', 'accounts.id')
            ->whereIn('payment_type', [1,4]);

        $balance = $balance->get()->sum('amount');
        $withdraw = $withdraw->get()->sum('amount');
        $result = [
            'accounts' => $accounts,
            'balance' => $balance,
            'withdraw' => $withdraw
        ];


        return response()->json($result);
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

        $account = new Account();
        $account->bank_id = $request->bank_id;
        $account->name = $request->name;
        $account->account_number = $request->account_number;
        $account->save();

        return response()->json($account);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank, Account $account)
    {
        $request->has('account_number') ? $account->account_number = $request->account_number : null;
        $request->has('name') ? $account->name = $request->name : null;
        $account->save();

        return response()->json($account);
    }

    /**
     * @param Bank $bank
     * @param Account $account
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Bank $bank, Account $account)
    {
        $account->delete();
        return response()->json($account);
    }

    public function getAllAccount(){
        $accounts = Account::select('id', 'name')->orderBy('name')->get();
        return response()->json($accounts);
    }

    /**
     * @param int $length
     * @return string
     */
    public static function generateAccountNumber($length = 11) {
        $characters = '0123456789';
        $randomString = '';

        while(true){
            $charactersLength = strlen($characters);
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $product = self::where('account_number', $randomString)
                ->first();

            if(!$product){
                break;
            }
        }

        return $randomString;
    }
}
