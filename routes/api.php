<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FollowController;

// Rotas públicas
Route::get('/users/search', [UserController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/logout',  [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me',       [AuthController::class, 'me']);
    });

    // Usuário autenticado — deve vir ANTES de {username}
    Route::put('/users/me',         [UserController::class, 'update']);
    Route::post('/users/me/avatar', [UserController::class, 'avatar']);

    Route::post('/users/{id}/follow',        [FollowController::class, 'follow']);
    Route::delete('/users/{id}/unfollow',    [FollowController::class, 'unfollow']);
    Route::get('/users/{id}/is-following',   [FollowController::class, 'isFollowing']);

});

// Rotas públicas com parâmetro dinâmico — devem vir POR ÚLTIMO
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);
Route::get('/users/{username}', [UserController::class, 'show']);

Route::get('/users/{id}/followers', [FollowController::class, 'followers']);
Route::get('/users/{id}/following', [FollowController::class, 'following']);