<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\ApiController;
use App\Rent;
use App\Room;
use App\Store;
use App\Traits\ApiResponser;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class RentController extends ApiController
{
    use ApiResponser;
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('room_id')){
            $rent = Rent::where('room_id', $request->room_id)
                ->orderBy('created_at', 'DESC')
                ->first();
            return $this->showOne($rent);
        }

        $rents = Rent::with(['room', 'staff'])
            ->orderBy('id', 'DESC')
            ->get();

        $total = $rents->count();

        $amount_transactions = $rents->sum('total');

        $collect = collect([
            'rents' => $rents,
            'total_tk' => $amount_transactions,
            'total_transactions' => $total
        ]);

        return $this->showAll($collect);
    }


    public function generateRandomString($length = 11)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('welcome');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $rent = new Rent();
        $request->has('room_id') ? $rent->room_id = $request->room_id : 0;
        $request->has('hotel_id') ? $rent->hotel_id = $request->hotel_id : 0;
        $request->has('staff_id') ? $rent->staff_id = $request->staff_id : 0;
        $request->has('name') ? $rent->client_name = $request->name : '';
        $request->has('father_name') ? $rent->father_name = $request->father_name : '';
        $request->has('ni_number') ? $rent->ni_number = $request->ni_number : '';
        $request->has('address') ? $rent->client_address = $request->address : '';
        $request->has('phone') ? $rent->client_phone = $request->phone : '';
        $request->has('advance') ? $rent->advance = $request->advance : '';

        // Mysql date & time
        $date = $request->checkin . ' '. date('H:i:s');

        $request->has('checkin') ? $rent->check_in = $date : '';

        $rent->save();

        $room = Room::findOrfail($request->room_id);
        $room->status = 'unavailable';
        $room->save();

        return response()->json($rent);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
        return $this->showOne($transaction);
    }

    public function showPrint(Request $request, int $id)
    {
        if ($request->ajax()) {
            $transaction = Transaction::with(['products.serials','serials', 'customer','seller'])
                ->where('id', '=', $id)
                ->first();
            foreach ($transaction->products as $product) {

            	$serials = $transaction->serials;
				$productSaleSerial = [];
				$warranty = '';
            	if($serials->count() > 0){
            		foreach($serials as $serial){
            			$isSerial = false;
            			$warranty = $serial->product_warranty;
            			foreach($product->serials as $productSerial){
            				if($serial->product_id === $productSerial->product_id){
								$isSerial = true;
							}
						}
						if($isSerial){
							$productSaleSerial[] = $serial;
						}
					}
				}
				$product->productWarranty = $warranty;
				$product->productSaleSerial = $productSaleSerial;
                $product->sale_quantity = $product->pivot->sale_quantity;
            }
            $setting = Store::find(1);

            $data = collect([
                'transaction' => $transaction,
                'setting' => $setting
            ]);
            return $data;
        }

        return view('welcome');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        //
        if ($request->ajax()) {
            $transaction = Transaction::with('products.seller')
                ->with('customer')
                ->findOrFail($request->id);
            return $this->showOne($transaction);
        }

        return view('welcome');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $transaction = Transaction::find($request->id);
        $transaction->products()->detach();
        if($transaction->delete()){
            return $this->successResponse($transaction, 200);
        }
    }
}
