<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ApiResponser;
    //
    public function index(Request $request){

        if($request->ajax()){
            $setting = Setting::all()->first();
            return $this->showOne($setting);
        }
    	return view('welcome');
    }


    public function store(Request $request){

    }

    public function update(Request $request, Setting $setting){
        $setting = $setting->fill($request->all());
        $setting->save();

        return $this->showOne($setting);
    }

    public function destroy(Setting $setting){

    }
}
