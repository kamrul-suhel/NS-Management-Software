<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;

class ServiceController extends ApiController
{
    //

    public function index(){
        $services = Service::all();
        $data = [];

        $totalService = $services->count();

        $data['services'] = $services;
        $data['total_count'] = $totalService;

        $due = Service::where('status', 'due')->get();
        $data['due'] = $due->count();
        $data['due_amount'] = number_format($due->sum('service_charge'), 2);



        $paid = Service::where('status', 'paid')->get();
        $data['paid'] = $paid->count();
        $data['paid_amount'] = number_format($paid->sum('service_charge'), 2);

        return response()->json($data);
    }

    public function store(Request $request){
        $service = new Service();

        $request->has('brand') ? $service->brand = $request->brand : '';
        $request->has('problem') ? $service->problem = $request->brand : '';
        $request->has('description') ? $service->description = $request->description : '';
        $request->has('service_charge') ? $service->service_charge = $request->service_charge : '';
        $request->has('status') ? $service->status = $request->status : '';
        $service->save();

        return $this->showOne($service);
    }

    public function update(Request $request, $id){
        $service = Service::findOrFail($id);

        $request->has('brand') ? $service->brand = $request->brand : '';
        $request->has('problem') ? $service->problem = $request->brand : '';
        $request->has('description') ? $service->description = $request->description : '';
        $request->has('service_charge') ? $service->service_charge = $request->service_charge : '';
        $request->has('status') ? $service->status = $request->status : '';

        $service->save();

        return $this->showOne($service);
    }

    public function destroy($id){
        $service = Service::findOrFail($id);
        $service->delete();

        return $this->showOne($service);
    }
}
