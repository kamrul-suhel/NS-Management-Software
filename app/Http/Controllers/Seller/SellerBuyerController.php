<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SellerBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $buyers = $seller->products()
            ->whereHas('transitions')
            ->with('transitions.buyer')
            ->get()
            ->pluck('transitions')
            ->collapse()
            ->pluck('buyer')
            ->unique('id')
            ->values();

        return $this->showAll($buyers);
    }
}
