<?php

use App\Http\Controllers\WorldController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('weather')->group(function () {
        Route::get('random-idea', [WeatherController::class, 'randomIdea']);
    });

    Route::prefix('world')->group(function () {
        Route::post('current', [WorldController::class, 'getCurrentWorld']);
    });
});
