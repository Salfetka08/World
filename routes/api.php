<?php

use App\Http\Controllers\WorldController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('world')->group(function () {
        Route::post('current', [WorldController::class, 'getCurrentWorld']);
    });
});
