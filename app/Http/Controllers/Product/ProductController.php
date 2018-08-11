<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends ApiController
{
    public function __construct()
    {
//        $this->middleware('client.credentials')->only(['index','show']);
//        $this->middleware('transform.input:'.ProductTransformer::class)->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with(['serials' => function($quary){
            $quary->where('is_sold', 0);
        }])->get();
        $totalProduct = $products->count();
        $totalStock = $products->sum(function($product){
            return $product->purchase_price * $product->quantity;
        });

        $avaliable_product = Product::where('status', 'available')->count();
        $unavaliable_product = Product::where('status', 'unavailable')->count();

        $data = collect([
            'products' => $products,
            'quantity_types' => Product::getQuantityType(),
            'total_stock' => number_format($totalStock,2,'.',','),
            'avaliable_product' => $avaliable_product,
            'unavaliable_product' => $unavaliable_product,
            'total_product' => $totalProduct
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
     * @param  \Illuminate\Http\Request  $request
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
        $product['seller_id'] = 1;
        $product = Product::create($product);

        // Product serials key with company
        $productSerialsWithCompany =[];
        $productCompany = [];
        foreach($totalCompanies as $currCompany){

            $company = [];
            $company['company_id'] = $currCompany->selectedCompany->id;
            $company['product_quantity'] = $currCompany->quantity;
            $productCompany[] = $company;

            if($currCompany->serials){
                foreach($currCompany->serials as $currSerial){
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
        if($request->has('categories')){
            $categoriesId = [];
            foreach(json_decode($request->categories) as $category){
                $categoriesId[] = $category->value;
            }
            $product->categories()->sync($categoriesId);
        }

        return $this->showOne($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->showOne($product, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        $product->fill($request->only([
            'name',
            'description',
            'purchase_price',
            'quantity',
            'quantity_type',
            'sale_price',
            'status'
        ]));

        if($request->has('categories') && !empty($request->categories)){
            $categoriesId = [];
            foreach(json_decode($request->categories) as $category){
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
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        $delete = $product->delete();
        if($delete){
            return $this->showOne($product);
        }
    }
}
