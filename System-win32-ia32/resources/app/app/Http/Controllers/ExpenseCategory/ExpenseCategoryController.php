<?php

namespace App\Http\Controllers\ExpenseCategory;

use App\ExpenseCategory;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenseCategoryController extends Controller
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
            $expenseCategory = ExpenseCategory::orderBy('id', 'DESC')->get();
            return $this->successResponse($expenseCategory, 200);
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
        $category = $request->all();
        $newCategory = ExpenseCategory::create($category);

        return $this->successResponse($newCategory, 200);

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
        $expenseCategory = ExpenseCategory::with('expenses')
            ->where('id', $id)
        ->get();
        return $this->successResponse($expenseCategory, 200);
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
        $updateExpenseCategory = $request->all();

        $expenseCategory = ExpenseCategory::findOrfail($id);
        $update = $expenseCategory->update($updateExpenseCategory);
        if($update){
            return $this->successResponse($expenseCategory, 200);
        }

        return $this->errorResponse('Category is not updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expenseCategory = ExpenseCategory::findOrfail($id);
        $delete = $expenseCategory->delete();
        if($delete){
          return   $this->successResponse(['success' => 1], 200);
        }
        return $this->errorResponse('Category is not avaliable', 200);
    }
}
