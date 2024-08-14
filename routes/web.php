<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/validate-cep', [LocationController::class, 'validateCep']);
Route::post('/calculate-distance', [LocationController::class, 'calculateDistance']);

