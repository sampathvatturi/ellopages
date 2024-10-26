<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::middleware('throttle:api')->group(function () {
    // Define your routes here
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});


// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    Route::controller(UserController::class)->group(function () {
        Route::get('/user/data','getUserData')->name('getUserData');
    });

});