<?php

namespace App\Http\Controllers;

use App\Models\Ev;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreEvRequest;
use App\Http\Requests\UpdateEvRequest;

class EvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Ev::all();
    }

    public function userEvs(){
        return Auth()->user()->evs;
        // return Auth::user()->evs;
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
     * @param  \App\Http\Requests\StoreEvRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEvRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ev  $ev
     * @return \Illuminate\Http\Response
     */
    public function show(Ev $ev)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ev  $ev
     * @return \Illuminate\Http\Response
     */
    public function edit(Ev $ev)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEvRequest  $request
     * @param  \App\Models\Ev  $ev
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEvRequest $request, Ev $ev)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ev  $ev
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ev $ev)
    {
        //
    }
}
