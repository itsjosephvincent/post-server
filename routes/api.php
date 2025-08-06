<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Facebook\FacebookController;
use App\Http\Controllers\Api\FacebookPage\FacebookPageController;
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
        'facebook-pages' => FacebookPageController::class,
    ]);

    Route::prefix('users')->group(function () {
        Route::get('/current/online', [UserController::class, 'user']);
        Route::put('/{uuid}/update-password', [UserController::class, 'updatePassword']);
    });

    Route::prefix('facebook-pages')->group(function () {
        Route::get('/next/post', [FacebookPageController::class, 'nextPost']);
        Route::get('/post/insights', [FacebookPageController::class, 'postInsights']);
        Route::get('/post/react/insights', [FacebookPageController::class, 'postReactInsights']);
    });
});
