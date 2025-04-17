<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalcController;
Route::get('/', function () {
    return view('welcome');
});

// 計算機
Route::get('/calc', [CalcController::class, 'calc']);
Route::post('/calculate', [CalcController::class, 'calculate'])->name('calculate');
