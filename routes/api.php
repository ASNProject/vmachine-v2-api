<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\DispenseController;
use App\Http\Controllers\Api\CustomerItemLimitController;
use App\Http\Controllers\Api\CustomerItemUsageController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\KeypadController;
use App\Http\Controllers\Api\LimitPeriodController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\ReportController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

// Customer
Route::apiResource('/customer', CustomerController::class);
// Route::delete('/customers/{uid}', [CustomerController::class, 'delete']);

// Role
Route::apiResource('/role', RoleController::class);

// Item Group 
Route::apiResource('/group', GroupController::class);
Route::post('/group/{group}/product', [GroupController::class, 'addProduct']);

// Product
Route::apiResource('/product', ProductController::class);

// Device
Route::apiResource('/device', DeviceController::class);

// Transaction
Route::post('/transaction', [TransactionController::class, 'transaction']);
Route::apiResource('/transactions', TransactionController::class);

// Configuration
Route::apiResource('/configuration', ConfigurationController::class);

Route::prefix('reports')->group(function () {
    Route::get('/transactions', [ReportController::class, 'transactions']);
    Route::get('/transactions/export', [ReportController::class, 'exportTransactions']);

    Route::get('/products/top', [ReportController::class, 'topProducts']);
    Route::get('/products/export', [ReportController::class, 'exportProducts']);

    Route::get('/devices/usage', [ReportController::class, 'deviceUsage']);
    Route::get('/devices/export', [ReportController::class, 'exportDevices']);
});

Route::delete('/reports/transactions/truncate', [ReportController::class, 'truncateTransactions']);