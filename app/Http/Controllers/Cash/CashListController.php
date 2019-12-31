<?php

namespace App\Http\Controllers\Cash;

use App\Cash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CashListController extends Controller
{
    public function list(){
        $cash = Cash::orderBy('id', 'DESC')
            ->get();

        $add = clone $cash;
        $minus = clone $cash;
        $add = $add->where('type', 1)->sum('amount');
        $minus = $minus->where('type', 2)->sum('amount');

        return response()->json([
            'cash' => $cash,
            'add' => $add,
            'minus' => $minus
        ]);
    }
}
