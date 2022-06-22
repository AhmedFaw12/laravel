<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvManufacturer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\EvManufacturerResource;
use App\Http\Requests\StoreEvManufacturerRequest;
use App\Http\Requests\UpdateEvManufacturerRequest;
use App\Http\Resources\EvManufacturerResourceCollection;

class EvManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return EvManufacturer::get();//return all data as collection
        // return EvManufacturerResource::collection(EvManufacturer::get());//using resource collection method

        //using ResourceCollection Class
        return new EvManufacturerResourceCollection(EvManufacturer::get());
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
     * @param  \App\Http\Requests\StoreEvManufacturerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(),[
            'name' => "required",
            'updated_by' => "required|exists:users,id",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 405);
            //return $validator->errors()
        }

        // dump($request->all());//return array

        $m = new EvManufacturer();
        $m->name = $request->name;
        $m->updated_by = $request->updated_by;
        $m->save();

        return $m;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EvManufacturer  $evManufacturer
     * @return \Illuminate\Http\Response
     */
    public function show(EvManufacturer $manufacturer)
    {
        // Log::info(json_encode($manufacturer));//logging into laravel.log
        return new EvManufacturerResource($manufacturer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EvManufacturer  $evManufacturer
     * @return \Illuminate\Http\Response
     */
    public function edit(EvManufacturer $manufacturer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEvManufacturerRequest  $request
     * @param  \App\Models\EvManufacturer  $evManufacturer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEvManufacturerRequest $request, EvManufacturer $manufacturer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EvManufacturer  $evManufacturer
     * @return \Illuminate\Http\Response
     */
    public function destroy(EvManufacturer $manufacturer)
    {
        //
    }
}
