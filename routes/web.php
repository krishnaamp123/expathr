<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test',[DashboardController::class,'getTest'])->name('getTest');
