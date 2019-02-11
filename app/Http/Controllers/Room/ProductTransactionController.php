<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\ApiController;
use App\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Room $product)
    {
        $products = $product->transitions;

        return $this->showAll($products);
    }
}
