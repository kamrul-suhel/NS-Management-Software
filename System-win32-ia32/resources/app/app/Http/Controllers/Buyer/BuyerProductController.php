<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
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
    public function index(Buyer $buyer)
    {
        $transitions = $buyer->transitions()
            ->with('product')
            ->get()
            ->pluck('product')
            ->unique()
            ->values();
        return $this->showAll($transitions);
    }
}
