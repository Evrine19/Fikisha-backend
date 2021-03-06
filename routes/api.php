<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\Fleet\FleetController;
use \App\Http\Controllers\API\Customer\CustomerController;
use \App\Http\Controllers\API\Auth\AuthController;
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

Route::group(['prefix'=>'v1'], function(){
    Route::group(['prefix'=>'auth'], function(){
        Route::post('login', [AuthController::class,'login']);
        Route::post('register', [AuthController::class,'register']);
    });
    Route::group(['middleware'=>'auth:api'], function(){
        Route::post('logout', [AuthController::class,'logout']);
        Route::apiResource('fleet', FleetController::class);
        Route::apiResource('customers', CustomerController::class);
        Route::post('dispatch', [FleetController::class,'dispatchVehicle']);
        Route::post('load', [FleetController::class,'loading']);
        Route::get('orders', [CustomerController::class,'getOrders']);

    });
});
