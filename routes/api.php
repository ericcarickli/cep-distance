<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;

Route::post('/calculate', [LocationController::class, 'calculate']);
Route::get('/distances', [LocationController::class, 'listDistances']);
