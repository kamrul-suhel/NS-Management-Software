<?php

namespace App\Http\Controllers\Expense;

use App\Expense;
use App\ExpenseCategory;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
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

            $expense = Expense::with('category')->get();
            $category = ExpenseCategory::orderBy('id', 'DESC')->get();
            $data = [
                'expenses' => $expense,
                'expense_categories' => $category
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
        $expsense = $request->all();
        $newExpense = Expense::create($expsense);
        if($newExpense){
            return $this->successResponse($newExpense, 200);
        }

        return $this->errorResponse('Someting is wrong', 200);
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

        $expense = Expense::findOrfail($id);
        $update_expense = $request->all();

        $update = $expense->update($update_expense);

        if($update){
            return $this->successResponse($expense, 200);
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
        $expense = Expense::findOrfail($id);
        $deleted = $expense->delete();

        if($deleted){
            return $this->successResponse($expense, 200);
        }
    }
}
