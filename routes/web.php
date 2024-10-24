<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json('Invalid authentication token',401);
});
