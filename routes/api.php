<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CityApiController;


//AUTH
Route::post('registerapi', [AuthApiController::class,'registerapi']);
Route::post('loginapi', [AuthApiController::class,'loginapi'])->name('loginapi');
Route::post('logoutapi', [AuthApiController::class,'logoutapi']);
Route::post('refreshapi', [AuthApiController::class,'refreshapi']);
Route::post('meapi', [AuthApiController::class,'meapi']);
Route::post('changePasswordapi', [AuthApiController::class,'changePasswordapi']);

//MASTER City
Route::get('city', [CityApiController::class,'index']);
Route::get('city/{id}', [CityApiController::class,'show']);
Route::post('city', [CityApiController::class,'store']);
Route::patch('city/{id}', [CityApiController::class,'update']);
Route::delete('city/{id}', [CityApiController::class,'destroy']);

