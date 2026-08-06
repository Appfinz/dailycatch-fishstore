<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminCuttingStyleController;
use App\Http\Controllers\Admin\AdminSettingController;

/*
|--------------------------------------------------------------------------
| Customer Storefront Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [StorefrontController::class, 'index'])->name('home');
Route::get('/catalog', [StorefrontController::class, 'catalog'])->name('catalog');
Route::get('/product/{slug}', [StorefrontController::class, 'productDetail'])->name('product.detail');
Route::get('/combos', [StorefrontController::class, 'combos'])->name('combos');
Route::get('/recipes', [StorefrontController::class, 'recipes'])->name('recipes');
Route::get('/recipes/{slug}', [StorefrontController::class, 'recipeDetail'])->name('recipe.detail');
Route::get('/locations', [StorefrontController::class, 'locations'])->name('locations');
Route::get('/contact', [StorefrontController::class, 'contact'])->name('contact');

Route::get('/cart', [StorefrontController::class, 'cart'])->name('cart');
Route::get('/checkout', [StorefrontController::class, 'checkout'])->name('checkout');
Route::get('/order/track/{orderNumber}', [StorefrontController::class, 'orderTrack'])->name('orders.track');

/*
|--------------------------------------------------------------------------
| Full-Fledged Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Order Management & Kanban
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/update-weight', [AdminOrderController::class, 'updateWeight'])->name('orders.update-weight');
    Route::post('/orders/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Product & Price Batch Updater
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::post('/products/quick-price-update', [AdminProductController::class, 'quickPriceUpdate'])->name('products.quick-price-update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Categories Management
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Coupons & Discounts
    Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::delete('/coupons/{id}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

    // Cutting Styles & Fees
    Route::get('/cutting-styles', [AdminCuttingStyleController::class, 'index'])->name('cutting-styles.index');
    Route::post('/cutting-styles', [AdminCuttingStyleController::class, 'store'])->name('cutting-styles.store');
    Route::delete('/cutting-styles/{id}', [AdminCuttingStyleController::class, 'destroy'])->name('cutting-styles.destroy');

    // Customers List
    Route::get('/customers', [AdminSettingController::class, 'customers'])->name('customers.index');

    // Store & Branch Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
