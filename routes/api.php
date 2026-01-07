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
use App\Http\Controllers\Api\ItemGroupController;
use App\Http\Controllers\Api\KeypadController;
use App\Http\Controllers\Api\LimitPeriodController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

// Customer
Route::apiResource('/customer', CustomerController::class);

// Item
Route::apiResource('/item', ItemController::class);

// Role
Route::apiResource('/role', RoleController::class);

// Dispense
Route::apiResource('/dispense', DispenseController::class);

// Customer Item Limit
Route::apiResource('/customer-item-limit', CustomerItemLimitController::class);

// Customer Item Usage
Route::apiResource('/customer-item-usage', CustomerItemUsageController::class);

// Item Group 
Route::apiResource('/item-group', ItemGroupController::class);

// Keypad
Route::apiResource('/keypad', KeypadController::class);

// Limit Period 
Route::apiResource('/limit-period', LimitPeriodController::class);


