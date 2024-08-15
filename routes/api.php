<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistanceController;

Route::get('/distances', [DistanceController::class, 'listDistances']);
Route::post('/calculate', [DistanceController::class, 'calculate']);
Route::post('/calculate-mass', [DistanceController::class, 'calculateMass']);
