<?php

namespace App\Http\Controllers;

use App\Models\SpecificationType;
use App\Http\Requests\StoreSpecificationTypeRequest;
use App\Http\Requests\UpdateSpecificationTypeRequest;

class SpecificationTypeController extends Controller
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
     * @param  \App\Http\Requests\StoreSpecificationTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSpecificationTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SpecificationType  $specificationType
     * @return \Illuminate\Http\Response
     */
    public function show(SpecificationType $specificationType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SpecificationType  $specificationType
     * @return \Illuminate\Http\Response
     */
    public function edit(SpecificationType $specificationType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSpecificationTypeRequest  $request
     * @param  \App\Models\SpecificationType  $specificationType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSpecificationTypeRequest $request, SpecificationType $specificationType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SpecificationType  $specificationType
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecificationType $specificationType)
    {
        //
    }
}
