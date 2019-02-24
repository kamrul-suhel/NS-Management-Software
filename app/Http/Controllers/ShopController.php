<?php

namespace App\Http\Controllers;

use App\Store;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    use ApiResponser;
    //
    public function index(){

        if(request()->ajax()){
            $shops = Store::all();
            return $this->showAll($shops);
        }
    	return view('welcome');
    }


    public function store(Request $request){
        $all = $request->all();

        $store = Store::create($all);
        return $this->showOne($store);
    }

    public function update(Request $request){
        $store = Store::findOrfail($request->id);
        $store = $store->fill($request->except(['id','created_at', 'updated_at']));
        $store->save();

        return $this->showOne($store);
    }

    public function show(Store $shop){
        return $this->showOne($shop);
    }

    public function destroy(Store $setting){

    }
}
