<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistanceController;

Route::post('/calculate', [DistanceController::class, 'calculate']);
Route::get('/distances', [DistanceController::class, 'listDistances']);
