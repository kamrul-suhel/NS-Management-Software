<?php

namespace App\Http\Controllers\Product;

use App\Company;
use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
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

        $perPage = $request->has('rowsPerPage') ? $request->rowsPerPage : 2;

        $shopId = $request->has('shopId') ? $request->shopId : null;

        $allSerial = $request->allSerial;
        $products = Product::with(['serials' => function ($quary) use ($allSerial) {
            if ($allSerial === 'true') {
                $quary->where('is_sold', 0);
            }
        }]);
        if ($shopId) {
            $products = $products->where('store_id', $shopId);
        }

        if($request->has('status') && $request->status === 'available'){
            $products = $products->where('status', 'available');
        }

        if($request->has('category_id')){
            $products = $products->leftJoin('category_product', 'products.id', '=', 'category_product.product_id');
            $products = $products->where('category_product.category_id', '=', $request->category_id);
        }

        if($request->has('search') && !empty($request->search)){
            $products = $products->where('name', 'LIKE', '%'.$request->search . '%');
        }

        // Check query type, if it is productpage then do pagination or return all data
        if($request->has('query_type') && $request->query_type === 'productPage'){
            $productsCalculate = $products->get();
            $products = $products->paginate($perPage);
        }else{
            $productsCalculate = $products->get();
            $products = $products->get();
        }

        $totalProduct = $productsCalculate->count();
        $totalProductStock = $productsCalculate->sum('quantity');
        $totalStock = $productsCalculate->sum(function($product){
            return $product->quantity * $product->purchase_price;
        });

        $avaliable_product = Product::where('status', 'available');
        if ($shopId) {
            $avaliable_product = $avaliable_product->where('store_id', $shopId);
        }
        $avaliable_product = $avaliable_product->count();
        $unavaliable_product = Product::where('status', 'unavailable');
        if ($shopId) {
            $unavaliable_product = $unavaliable_product->where('store_id', $shopId);
        }
        $unavaliable_product = $unavaliable_product->count();

        // If scanned barcode or IMEI code
        $selectedProduct = new Product();
        if ($request->has('code') && $request->code !== 1) {
            $code = $request->code;
            $selectedProduct = $selectedProduct->with(['serials' => function ($query) use ($code) {
                $query->where('barcode', $code)
                    ->orWhere('imei', $code)
                ->where('is_sold', 0);
            }])
                ->whereHas('serials', function ($query) use ($code) {
                    $query->where('barcode', $code)->orWhere('imei', $code);
                    $query->where('is_sold', 0);
                })->first();
        }

        $data = collect([
            'products' => $products,
            'quantity_types' => Product::getQuantityType(),
            'total_stock' => number_format($totalStock, 2, '.', ','),
            'avaliable_product' => $avaliable_product,
            'unavaliable_product' => $unavaliable_product,
            'total_product' => $totalProduct,
            'stock_product' => $totalProductStock,
            'selected_product' => $selectedProduct
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
        // Product create
        $product = $request->except(['totalCompanies', 'categories', 'product_type']);
        $product['image'] = '1.jpg';
        //change this when auth is set
        $product['seller_id'] = $request->seller_id;

        $product = Product::create($product);

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
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product = Product::with(['companies','categories','serials.company'])
            ->findOrFail($product->id);
        return $this->showOne($product, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        $product->fill($request->only([
            'name',
            'description',
            'sale_price',
            'purchase_price',
            'status'
        ]));

        // If product has category then it will link with category in pivot table
        if ($request->has('categories')) {
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
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        //
        $product->serials()->delete();
        $delete = $product->delete();
        if ($delete) {
            return $this->showOne($product);
        }
    }
}
