<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\ProfileUserController;
use App\Http\Controllers\Admin\DashboardAdminController;

Route::get('/', function () {
    return view('welcome');
});

//AUTH
Route::get('/login',[AuthController::class,'getLogin'])->name('login');
Route::post('/login',[AuthController::class,'postLogin'])->name('postLogin');
Route::get('/register', [AuthController::class, 'getRegister'])->name('getRegister');
Route::post('/register', [AuthController::class, 'postRegister'])->name('postRegister');

// Password Reset Routes
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'reset'])->name('password.update');

// Email Verification Routes
Route::get('email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice')->middleware('auth');
Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify')->middleware(['signed']);
Route::post('email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::get('/test', [DashboardUserController::class, 'getTest'])->name('getTest');
        Route::get('/team', [DashboardUserController::class, 'getTeam'])->name('getTeam');
        Route::post('/logout',[AuthController::class,'postLogout'])->name('postLogout');
        Route::get('/profile', [ProfileUserController::class, 'getProfile'])->name('getProfile');
        Route::get('/profile/edit/{id}', [ProfileUserController::class, 'editProfile'])->name('editProfile');
        Route::put('/profile/update/{id}', [ProfileUserController::class, 'updateProfile'])->name('updateProfile');
    });
});

// USER
Route::get('/user', [AuthController::class, 'getUser'])->name('getUser');
Route::get('/user/create', [AuthController::class, 'addUser'])->name('addUser');
Route::post('/user/create', [AuthController::class, 'storeUser'])->name('storeUser');
Route::get('/user/update/{id}', [AuthController::class, 'editUser'])->name('editUser');
Route::put('/user/update/{id}', [AuthController::class, 'updateUser'])->name('updateUser');
Route::delete('/user/destroy/{id}', [AuthController::class, 'destroyUser'])->name('destroyUser');

Route::get('/admin', [DashboardAdminController::class, 'getAdmin'])->name('getAdmin');
