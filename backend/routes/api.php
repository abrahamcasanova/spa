<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EnumController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::post('/users/{user}/image', [UserController::class, 'storeImage']);
    Route::get('/status', [EnumController::class, 'status']);
    Route::get('/roles', [EnumController::class, 'roles']);
    Route::get('/dashboard/resume', [DashboardController::class, 'resume']);
});
