<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

// Auth Endpoints
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/roles', function () {
    return Role::with('permissions')->get();
});
Route::get('/test-user', function () {
    $user = \App\Models\User::with('role.permissions')->first();

    return [
        'user' => $user,
        'hasCreateUserPermission' => $user?->hasPermission('createUser') ?? false,
    ];
});
