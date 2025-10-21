<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{SalesController, OrdersController, StocksController, IncomesController};


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('api.key')->group(function () {
    Route::get('/sales', [SalesController::class, 'index']);
    Route::get('/orders', [OrdersController::class, 'index']);
    Route::get('/stocks', [StocksController::class, 'index']);
    Route::get('/incomes', [IncomesController::class, 'index']);
});
