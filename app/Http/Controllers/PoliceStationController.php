<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePoliceStationRequest;
use App\Http\Requests\UpdatePoliceStationRequest;
use App\Models\PoliceStation;

class PoliceStationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StorePoliceStationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePoliceStationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PoliceStation  $policeStation
     * @return \Illuminate\Http\Response
     */
    public function show(PoliceStation $policeStation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PoliceStation  $policeStation
     * @return \Illuminate\Http\Response
     */
    public function edit(PoliceStation $policeStation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePoliceStationRequest  $request
     * @param  \App\Models\PoliceStation  $policeStation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePoliceStationRequest $request, PoliceStation $policeStation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PoliceStation  $policeStation
     * @return \Illuminate\Http\Response
     */
    public function destroy(PoliceStation $policeStation)
    {
        //
    }
}
