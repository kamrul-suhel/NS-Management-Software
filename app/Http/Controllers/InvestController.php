<?php

namespace App\Http\Controllers;

use App\Invest;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class InvestController extends ApiController
{
    use ApiResponser;

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $invests = Invest::all();
        return $this->showAll($invests);
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
        $invest = new Invest();

        $invest->title = $request->title;
        $invest->description = $request->description;
        $invest->total = $request->total;

        $invest->save();

        return $this->showOne($invest);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function show(Invest $invest)
    {
        //
        return $this->showOne($invest);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function edit(Invest $invest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invest $invest)
    {
        //


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invest $invest)
    {
        //
    }
}
