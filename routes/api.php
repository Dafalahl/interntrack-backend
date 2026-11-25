<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/jobs', [JobController::class, 'index']);
        Route::get('/jobs/{id}', [JobController::class, 'show']);
        Route::post('/jobs', [JobController::class, 'store']);

        Route::post('/jobs/{id}/applications', [ApplicationController::class, 'store']);
        Route::get('/applications/me', [ApplicationController::class, 'myApplications']);
        Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
    });
});