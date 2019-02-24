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

        $shopId = $request->has('shopId') ? $request->shopId : null;

        $allSerial = $request->allSerial;
        $products = Product::with(['serials' => function ($quary) use ($allSerial) {
            if (!$allSerial) {
                $quary->where('is_sold', 0);
            }
        }])
            ->with('companies');
        if ($shopId) {
            $products = $products->where('store_id', $shopId);
        }

        $products = $products->get();

        $totalProduct = $products->count();
        $totalStock = $products->sum('quantity');

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
                $query->where('barcode', $code)->orWhere('imei', $code)->get();
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
        $totalCompanies = json_decode($request->totalCompanies, true);

        // Product create
        $product = $request->except(['totalCompanies', 'categories', 'product_type']);
        $product['image'] = '1.jpg';
        //change this when auth is set
        $product['seller_id'] = $request->seller_id;

        $product['is_barcode'] = $request->product_type;

        $product = Product::create($product);

        // Product serials key with company
        $productSerialsWithCompany = [];
        $productCompany = [];
        foreach ($totalCompanies as $currCompany) {
            $company = [];
            $company['company_id'] = $currCompany['selectedCompany']['id'];
            $company['product_quantity'] = $currCompany['quantity'];

            $productCompany[] = $company;
            if ($currCompany['serials'] && !empty($currCompany['serials'])) {
                foreach ($currCompany['serials'] as $currSerial) {
                    $serial = [];
                    $serial['is_sold'] = 0;
                    $serial['color'] = $currSerial['color'];
                    $serial['barcode'] = $currSerial['barcode'];
                    $serial['imei'] = $currSerial['imei'];
                    $serial['product_warranty'] = $currCompany['product_warranty'];
                    $serial['company_id'] = $currCompany['selectedCompany']['id'];
                    $productSerialsWithCompany[] = $serial;
                }
            } else {
                $serial = [];
                $serial['is_sold'] = 0;
                $serial['color'] = '';
                $serial['barcode'] = '';
                $serial['imei'] = '';
                $serial['product_warranty'] = $currCompany['product_warranty'];
                $serial['company_id'] = $currCompany['selectedCompany']['id'];
                $productSerialsWithCompany[] = $serial;
            }
        }

        // Save product company.
        $product->companies()->attach($productCompany);

        // Save Product serial
        $product->serials()->createMany($productSerialsWithCompany);

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
