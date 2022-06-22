<?php

namespace App\Http\Controllers;

use App\Models\EvModel;
use App\Http\Requests\StoreEvModelRequest;
use App\Http\Requests\UpdateEvModelRequest;

class EvModelController extends Controller
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
     * @param  \App\Http\Requests\StoreEvModelRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEvModelRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EvModel  $evModel
     * @return \Illuminate\Http\Response
     */
    public function show(EvModel $evModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EvModel  $evModel
     * @return \Illuminate\Http\Response
     */
    public function edit(EvModel $evModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEvModelRequest  $request
     * @param  \App\Models\EvModel  $evModel
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEvModelRequest $request, EvModel $evModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EvModel  $evModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(EvModel $evModel)
    {
        //
    }
}
