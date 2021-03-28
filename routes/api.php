<?php

use App\Http\Controllers\GameFinderController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'games'], function () {
    Route::get('/', GameFinderController::class);
});
