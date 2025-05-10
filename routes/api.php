<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReorderRequestController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Register and Login Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// CSRF cookie route for Sanctum authentication
Route::get('/sanctum/csrf-cookie', [\Laravel\Sanctum\Http\Controllers\CsrfCookieController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Product CRUD (Admin only)
    Route::apiResource('products', ProductController::class)->middleware('role:admin');

    // Cart (All authenticated users)
    Route::apiResource('carts', CartController::class)->only(['index', 'store', 'destroy']);

    // Orders (All authenticated users)
    Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'show']);
    Route::post('orders/{order}/approve', [OrderController::class, 'approve'])->middleware('role:staff');
    Route::post('orders/{order}/deliver', [OrderController::class, 'deliver'])->middleware('role:staff');

    // Reorder Requests (Admin and Warehouse Managers)
    Route::apiResource('reorder-requests', ReorderRequestController::class)
        ->middleware('role:admin,warehouse_manager');

    // Dashboard (Admin only)
    Route::get('dashboard', [DashboardController::class, 'index'])->middleware('role:admin');
});
