<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\LocationApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerAddressController;

/*
|--------------------------------------------------------------------------
| API Routes (v1)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    // Products & Categories
    Route::get('/categories', [ProductApiController::class, 'categories']);
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{slug}', [ProductApiController::class, 'show']);

    // Cart Management
    Route::get('/cart', [CartApiController::class, 'get']);
    Route::post('/cart/add', [CartApiController::class, 'add']);
    Route::post('/cart/update', [CartApiController::class, 'update']);
    Route::post('/cart/remove', [CartApiController::class, 'remove']);
    Route::post('/cart/apply-coupon', [CartApiController::class, 'applyCoupon']);
    Route::post('/cart/clear', [CartApiController::class, 'clear']);

    // Location & 3KM Radius Validation
    Route::post('/validate-location', [LocationApiController::class, 'validateRadius']);

    // Mobile OTP Authentication Flow
    Route::post('/auth/send-otp', [CustomerAuthController::class, 'sendOtp']);
    Route::post('/auth/verify-otp', [CustomerAuthController::class, 'verifyOtp']);
    Route::get('/auth/me', [CustomerAuthController::class, 'me']);
    Route::post('/auth/logout', [CustomerAuthController::class, 'logout']);

    // Customer Saved Delivery Addresses
    Route::get('/addresses', [CustomerAddressController::class, 'index']);
    Route::post('/addresses', [CustomerAddressController::class, 'store']);
    Route::delete('/addresses/{id}', [CustomerAddressController::class, 'destroy']);

    // Orders
    Route::post('/orders', [OrderApiController::class, 'store']);
    Route::get('/orders/{orderNumber}', [OrderApiController::class, 'show']);
    Route::post('/orders/{orderNumber}/cancel', [OrderApiController::class, 'cancel']);
});
