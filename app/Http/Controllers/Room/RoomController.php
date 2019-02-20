<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\ApiController;
use App\Room;
use Illuminate\Http\Request;

class RoomController extends ApiController
{
    public function __construct()
    {
//        $this->middleware('client.credentials')->only(['index','show']);
//        $this->middleware('transform.input:'.ProductTransformer::class)->only(['store','update']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $shopId = $request->has('shopId') ? $request->shopId : null;

        $rooms = Room::where('hotel_id', $shopId);

        if($request->has('check_in')){
            $rooms = $rooms->where('status', 'available')
                ->get();
            return $this->showAll($rooms);
        }

        $rooms = $rooms->get();

        $totalRooms = $rooms->count();
        $totalStock = $rooms->sum(function ($room) {
            return $room->price;
        });

        $avaliable_rooms = Room::where('status', 'available');
        $avaliable_rooms = $avaliable_rooms->where('hotel_id', $shopId);
        $avaliable_rooms = $avaliable_rooms->count();

        $unavaliable_rooms = Room::where('status', 'unavailable');
        $unavaliable_rooms = $unavaliable_rooms->where('hotel_id', $shopId);
        $unavaliable_rooms = $unavaliable_rooms->count();

        $data = collect([
            'rooms' => $rooms,
            'total_stock' => number_format($totalStock, 2, '.', ','),
            'total_rooms' => $totalRooms,
            'avaliable_rooms' => $avaliable_rooms,
            'unavaliable_rooms' => $unavaliable_rooms
        ]);
        return $this->showAll($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Room create
        $room = new Room();
        $room->hotel_id = $request->hotel_id;
        $room->room_number = $request->room_number;
        $room->title = $request->title;
        $room->description = $request->description;
        $room->status = Room::ABAILABLE_PRODUCT;
        $room->price = $request->price;
        $room->additional_price = $request->additional_price;
        $room->status = $request->status;
        $room->image = '1.jpg';
        $room->save();

        return $this->showOne($room);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Room $product
     * @return \Illuminate\Http\Response
     */
    public function show(Room $product)
    {
        return $this->showOne($product, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Room $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Room $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        $request->has('room_number') ? $room->room_number = $request->room_number : '';
        $request->has('hotel_id') ? $room->hotel_id = $request->hotel_id : '';
        $request->has('title') ? $room->title = $request->title : '';
        $request->has('description') ? $room->description = $request->description : '';
        $request->has('price') ? $room->price = $request->price : '';
        $request->has('additional_price') ? $room->additional_price = $request->additional_price : '';
        $request->has('status') ? $room->status = $request->status : '';

        $room->save();
        return $this->showOne($room);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Room $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $product)
    {
        //
        $delete = $product->delete();
        if ($delete) {
            return $this->showOne($product);
        }
    }
}
