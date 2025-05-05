<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReorderRequestController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Product CRUD (Admin only)
    Route::apiResource('products', ProductController::class)->middleware('role:admin');

    // Cart
    Route::apiResource('carts', CartController::class)->only(['index', 'store', 'destroy']);

    // Orders
    Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'show']);
    Route::post('orders/{order}/approve', [OrderController::class, 'approve'])->middleware('role:staff');
    Route::post('orders/{order}/deliver', [OrderController::class, 'deliver'])->middleware('role:staff');

    // Reorder Requests
    Route::apiResource('reorder-requests', ReorderRequestController::class)
        ->middleware('role:admin,warehouse_manager');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->middleware('role:admin');
});