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
        //
        $totalCompanies = json_decode($request->totalCompanies);


        // Product create
        $product = $request->except('totalCompanies');
        $product['image'] = '1.jpg';
        //change this when auth is set
        $product['seller_id'] = $request->sellerId;
        $product = Room::create($product);

        // Product serials key with company
        $productSerialsWithCompany = [];
        $productCompany = [];
        foreach ($totalCompanies as $currCompany) {

            $company = [];
            $company['company_id'] = $currCompany->selectedCompany->id;
            $company['product_quantity'] = $currCompany->quantity;
            $productCompany[] = $company;
            if ($currCompany->serials) {
                foreach ($currCompany->serials as $currSerial) {
                    $serial = [];
                    $serial['is_sold'] = 0;
                    $serial['product_serial'] = $currSerial;
                    $serial['product_warranty'] = $currCompany->product_warranty;
                    $serial['company_id'] = $currCompany->selectedCompany->id;
                    $productSerialsWithCompany[] = $serial;
                }
            }
        }

        $companies = $product->companies()->attach($productCompany);
        $serial = $product->serials()->createMany($productSerialsWithCompany);

        // If product has category then it will link with category in pivot table
        if ($request->has('categories')) {
            $categoriesId = [];
            foreach (json_decode($request->categories) as $category) {
                $categoriesId[] = $category->value;
            }
            $product->categories()->sync($categoriesId);
        }

        return $this->showOne($product);
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
    public function update(Request $request, Room $product)
    {
        //
        $product->fill($request->only([
            'name',
            'description',
            'purchase_price',
            'quantity_type',
            'sale_price',
            'status'
        ]));
        $product->quantity = $product->quantity + $request->quantity;

        // Product serials key with company
        $totalCompanies = json_decode($request->totalCompanies);
        $productSerialsWithCompany = [];
        $productCompany = [];
        foreach ($totalCompanies as $currCompany) {

            $company = [];
            $company['company_id'] = $currCompany->selectedCompany->id;
            $company['product_quantity'] = $currCompany->quantity;
            $productCompany[] = $company;
            if ($currCompany->serials) {
                foreach ($currCompany->serials as $currSerial) {
                    $serial = [];
                    $serial['is_sold'] = 0;
                    $serial['product_serial'] = $currSerial;
                    $serial['product_warranty'] = $currCompany->product_warranty;
                    $serial['company_id'] = $currCompany->selectedCompany->id;
                    $productSerialsWithCompany[] = $serial;
                }
            }
        }

        $companies = $product->companies()->syncWithoutDetaching($productCompany);
        $serial = $product->serials()->createMany($productSerialsWithCompany);

        if ($request->has('categories') && !empty($request->categories)) {
            $categoriesId = [];
            foreach (json_decode($request->categories) as $category) {
                $categoriesId[] = $category->value;
            }
            $product->categories()->sync($categoriesId);
        }

        $product->save();
        return $this->showOne($product);
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
