<?php

namespace App\Http\Controllers\Cash;

use App\Cash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CashDestroyController extends Controller
{
    public function destroy($id){
        $cash = Cash::findOrFail($id);
        $cash->delete();

        return response()->json(
            [
                'success' => true
            ]
        );
    }
}
