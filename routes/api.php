<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', App\Http\Controllers\Api\ProductController::class);
    Route::apiResource('stores', App\Http\Controllers\Api\StoreController::class);
    Route::apiResource('shopping-records', App\Http\Controllers\Api\ShoppingRecordController::class);

    Route::get('products/{product}/price-history', App\Http\Controllers\Api\PriceHistoryController::class);
    Route::get('products/{product}/store-comparison', App\Http\Controllers\Api\StoreComparisonController::class);

    Route::get('/reports/most-purchased-products', App\Http\Controllers\Api\Reports\MostPurchasedProductsController::class);

    Route::get('/dashboard-stats', App\Http\Controllers\DashboardStatsController::class);
});

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
