<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\API'], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/user', [UserController::class, 'profile'])->name('profile');
    });
});
