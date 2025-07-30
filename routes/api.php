<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Facebook\FacebookController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'facebook' => FacebookController::class,
        'users' => UserController::class,
    ]);

    Route::get('/users/current/user', [UserController::class, 'user']);
    Route::put('/users/{uuid}/update-password', [UserController::class, 'updatePassword']);
});
