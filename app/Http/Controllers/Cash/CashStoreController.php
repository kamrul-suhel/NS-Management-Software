<?php

namespace App\Http\Controllers\Cash;

use App\Cash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CashStoreController extends Controller
{
    public function store(Request $request){
        $cash = new Cash();
        $request->has('notes') ? $cash->notes = $request->notes : null;
        $request->has('amount') ? $cash->amount = $request->amount : null;
        $request->has('type') ? $cash->type = $request->type : null;

        $cash->save();

        return response()->json($cash);
    }

    public function update(Request $request, $id){
        $cash = Cash::findOrFail($id);
        $request->has('notes') ? $cash->notes = $request->notes : null;
        $request->has('amount') ? $cash->amount = $request->amount : null;
        $request->has('type') ? $cash->type = $request->type : null;

        $cash->save();

        return response()->json($cash);
    }
}
