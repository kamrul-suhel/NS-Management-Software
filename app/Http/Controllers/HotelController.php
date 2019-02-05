<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    use ApiResponser;
    //
    public function index(){

        if(request()->ajax()){
            $hotels = Hotel::all();
            return $this->showAll($hotels);
        }
    	return view('welcome');
    }


    public function store(Request $request){
        $all = $request->all();

        $hotel = Hotel::create($all);
        return $this->showOne($hotel);
    }

    public function update(Request $request){
        $hotel = Hotel::findOrfail($request->id);
        $hotel = $hotel->fill($request->except(['id','created_at', 'updated_at']));
        $hotel->save();

        return $this->showOne($hotel);
    }

    public function show(Hotel $shop){
        return $this->showOne($shop);
    }

    public function destroy(Hotel $setting){

    }
}
