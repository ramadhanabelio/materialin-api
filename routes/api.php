<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\User\OrderUserController;
use App\Http\Controllers\User\ProductUserController;
use App\Http\Controllers\Admin\OrderStatusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/user/register', [UserAuthController::class, 'register']);
Route::post('/user/login', [UserAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/user/logout', [UserAuthController::class, 'logout']);

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/products', [ProductUserController::class, 'index']);
    Route::get('/products/{id}', [ProductUserController::class, 'show']);

    Route::post('/orders', [OrderUserController::class, 'store']);
    Route::get('/orders', [OrderUserController::class, 'index']);
    Route::get('/orders/{id}', [OrderUserController::class, 'show']);
});

Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/admin/logout', [AdminAuthController::class, 'logout']);

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}/status', [OrderStatusController::class, 'update']);
});
