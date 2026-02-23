<?php

use App\Http\Controllers\MemoryWeatherController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('weather')->group(function () {
        Route::get('random-idea', [MemoryWeatherController::class, 'randomIdea']);
    });
});
