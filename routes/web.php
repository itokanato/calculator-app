<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculatorController;
Route::get('/', function () {
    return view('welcome');
});

// 電卓
Route::get('/calculator', [CalculatorController::class, 'index']);