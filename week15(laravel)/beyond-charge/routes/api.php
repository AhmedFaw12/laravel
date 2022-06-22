<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EvManufacturerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//defining route login
Route::post("/login", [UserController::class, "login"])->name("login");

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix("/")->middleware("auth:sanctum")->group(function(){
    //making API routes For EvManufacturer controller using apiResource() Method
    Route::apiResource("manufacturer", EvManufacturerController::class);
    Route::apiResource("ev", EvController::class);
    Route::get("/my_evs", [EvController::class, "userEvs"]);
});



// Route::apiResource("user", UserController::class);

