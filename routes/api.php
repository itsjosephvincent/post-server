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

    Route::get('/users/current/online', [UserController::class, 'user']);
    Route::put('/users/{uuid}/update-password', [UserController::class, 'updatePassword']);

    Route::get('/facebook-pages/{uuid}/posts', [FacebookPageController::class, 'posts']);
    Route::get('/facebook-pages/next/new/posts', [FacebookPageController::class, 'nextPost']);
});
