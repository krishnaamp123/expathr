<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\Admin\DashboardAdminController;

Route::get('/', function () {
    return view('welcome');
});

//AUTH
Route::get('/login',[AuthController::class,'getLogin'])->name('getLogin');
Route::post('/login',[AuthController::class,'postLogin'])->name('postLogin');

Route::get('/user',[DashboardUserController::class,'getUser'])->name('getUserr');
Route::get('/team',[DashboardUserController::class,'getTeam'])->name('getTeam');
Route::get('/admin',[DashboardAdminController::class,'getAdmin'])->name('getAdmin');
