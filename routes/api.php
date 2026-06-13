<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('inventories', InventoryController::class);

    Route::apiResource('logs', LogController::class)->except(['destroy']);
    Route::delete('/logs/{log}', [LogController::class, 'destroy'])->middleware('admin');

    Route::apiResource('reports', ReportController::class)->except(['destroy']);
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->middleware('admin');

    Route::middleware('admin')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
    });
});