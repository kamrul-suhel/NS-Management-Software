<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function __construct()
    {
//        $this->middleware('client.credentials')->only(['show','index']);
//        $this->middleware('auth:api')->except(['show','index']);
        // $this->middleware('transform.input:'.CategoryTransformer::class)->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::root();
        $childrens = $categories->getDescendantsAndSelf();
        return $this->showAll($childrens, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name'          => 'required',
            'description'   => 'required',
        ];

        $this->validate($request, $rules);

        $root = Category::root();
    
        if(!$root) {
            $category = Category::create($request->all());
            $category->makeRoot();
            return $this->showOne($category, 201);
        }

        // check if parent id is set
        if($request->parent_id){
           $parent = Category::find($request->parent_id);
           $category = Category::create($request->all());

           $category->makeChildOf($parent);
           return $this->showOne($category, 201);
        }
        $category = $root->children()->create($request->all());


        return $this->showOne($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->showOne($category, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->fill($request->only([
            'name',
            'description',
        ]));
        if($category->isClean()){
            return $this->errorResponse("You need to specifi any diffrent value to update", 422);
        }
        $category->save();
        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->id == 1){
            return $this->errorResponse('You can not delete this category', 422);
        }
        $category->delete();
        return $this->showOne($category, 505);
    }
}
