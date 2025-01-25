<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

//Route::post('/exists', [UserController::class, 'checkUsername']);

Route::get('/register', function() {
    return "Registrando usuario";
});

Route::get('/login', function() {
    return "Iniciando sesión";
});

// Rutas de autenticación
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::get('/posts/{id}', [PostController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'create']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'delete']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/follow/{followedId}', [FollowerController::class, 'follow']);
    Route::delete('/unfollow/{followedId}', [FollowerController::class, 'unfollow']);
});

Route::post('/users/exists', [UserController::class, 'checkUsername']);