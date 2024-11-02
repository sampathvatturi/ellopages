<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\CategoriesController;
use App\Http\Controllers\v1\UserController;
use App\Http\Middleware\AuthenticateSanctumOrApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('throttle:api')->group(function () {
    // Define your routes here
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/register-apple', [AuthController::class, 'registerApple']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/validate-email', [AuthController::class, 'validateEmail']);
    Route::post('/validate-otp', [AuthController::class, 'validateOtp']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});


Route::prefix('v1')->middleware(AuthenticateSanctumOrApiKey::class)->group(function () {

    Route::controller(UserController::class)->group(function () {
        Route::get('/user/data','getUserData')->name('getUserData');
    });
    
    Route::controller(CategoriesController::class)->group(function () {
        Route::get('/get-categories', 'getCategories')->name('getCategories');
    });

});