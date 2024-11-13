<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\Admin\DashboardAdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user',[DashboardUserController::class,'getUser'])->name('getUser');
Route::get('/team',[DashboardUserController::class,'getTeam'])->name('getTeam');
Route::get('/admin',[DashboardAdminController::class,'getAdmin'])->name('getAdmin');
