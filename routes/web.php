<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/validate-cep', [LocationController::class, 'validateCep']);
Route::post('/calculate-distance', [LocationController::class, 'calculateDistance']);

