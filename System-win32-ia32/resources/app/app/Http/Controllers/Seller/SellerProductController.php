<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\Transformers\SellerTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.input:'.SellerTransformer::class)->only(['store','update','destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }


    public function store(Request $request, User $seller){
        $rules = [
            'name'          => 'required',
            'description'   => 'required',
            'quantity'      => 'required|integer|min:1',
            'image'         => 'required|image'
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['image'] = $request->image->store('');

        $data['seller_id'] = $seller->id;
        $data['status'] = Product::UNAVAILABLE_PRODUCT;

        $product = Product::create($data);

        return $this->showOne($product, 201);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {

        $rules = [
            'quantity'      => 'integer|min:1',
            'status'        => 'in:'.Product::ABAILABLE_PRODUCT .','.Product::UNAVAILABLE_PRODUCT,
            'image'         => 'image'
        ];

        $this->validate($request, $rules);

        $this->checkSeller($seller, $product);
        $product->fill($request->only([
            'name',
            'description',
            'quantity',
        ]));

        if($request->has('status')){
            $product->status = $request->status;

            if($product->isAbaliable() && $product->categories()->count() == 0){
                return $this->errorResponse("An active product must have a one category", 409);
            }
        }

        if($product->isClean()){
            return $this->errorResponse('You must change somethig to edit this ', 422);
        }

        // see the image
        if($request->hasFile('image')){
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }

        $product->save();

        return $this->showOne($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);

        Storage::delete($product->image);

        $product->delete();

        return $this->showOne($product);
    }

    private function checkSeller(Seller $seller, Product $product){
        if($seller->id != $product->seller_id){
            throw new HttpException(402, 'The specified seller is not the actual seller of the product');
        }
    }
}
