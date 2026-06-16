<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard-stats', [InventoryController::class, 'dashboardStats']);

    Route::apiResource('inventories', InventoryController::class);

    Route::apiResource('logs', LogController::class)->only(['index', 'show']);
    Route::delete('/logs/{log}', [LogController::class, 'destroy'])->middleware('admin');

    Route::middleware('admin')->group(function () {
        Route::get('/staff', [AuthController::class, 'index']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::put('/staff/{id}', [AuthController::class, 'update']);
        Route::delete('/staff/{id}', [AuthController::class, 'destroy']);
    });
});