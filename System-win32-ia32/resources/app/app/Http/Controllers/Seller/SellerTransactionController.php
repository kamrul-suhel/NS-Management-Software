<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;

class SellerTransactionController extends ApiController
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
    public function index(Seller $seller)
    {
        $transitions = $seller
            ->products()
            ->whereHas('transitions')
            ->with('transitions')
            ->get()
            ->pluck('transitions')
            ->collapse();

        return $this->showAll($transitions);

    }
}
