<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CityApiController;


//AUTH
Route::post('register', [AuthApiController::class,'register']);
Route::post('login', [AuthApiController::class,'login'])->name('login');
Route::post('logout', [AuthApiController::class,'logout']);
Route::post('refresh', [AuthApiController::class,'refresh']);
Route::post('me', [AuthApiController::class,'me']);
Route::post('changePassword', [AuthApiController::class,'changePassword']);

//MASTER City
Route::get('city', [CityApiController::class,'index']);
Route::get('city/{id}', [CityApiController::class,'show']);
Route::post('city', [CityApiController::class,'store']);
Route::patch('city/{id}', [CityApiController::class,'update']);
Route::delete('city/{id}', [CityApiController::class,'destroy']);

