<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

// Auth Endpoints
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

Route::middleware('auth:sanctum')->group(function () {

    /**
     * User Endpoints
     */
    // Custom routes must be defined before resource routes to avoid conflicts
    Route::get('/users/all', [UserController::class, 'all'])->middleware('permission:viewUser');
    Route::patch('/users/{user}/restore', [UserController::class, 'restore'])->middleware('permission:editUser');
    
    Route::apiResource('users', UserController::class)->middleware([
        'index' => 'permission:viewUser',
        'show' => 'permission:viewUser',
        'store' => 'permission:createUser',
        'update' => 'permission:editUser',
        'destroy' => 'permission:deleteUser',
    ]);

    /**
     * Role Endpoints
     */
    // Custom routes must be defined before resource routes to avoid conflicts
    Route::patch('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])->middleware('permission:editRole');
    
    Route::apiResource('roles', RoleController::class)->middleware([
        'index' => 'permission:viewRole',
        'show' => 'permission:viewRole',
        'store' => 'permission:createRole',
        'update' => 'permission:editRole',
    ]);

    /**
     * Permission Endpoints
     */
    Route::apiResource('permissions', PermissionController::class)->middleware([
        'index' => 'permission:viewPermission',
        'show' => 'permission:viewPermission',
        'store' => 'permission:createPermission',
        'update' => 'permission:editPermission',
    ]);

    /**
     * Client Endpoints
     */
    // Custom routes must be defined before resource routes to avoid conflicts
    Route::get('/clients/all', [ClientController::class, 'all'])->middleware('permission:viewClient');
    Route::get('/clients/paginated', [ClientController::class, 'paginated'])->middleware('permission:viewClient');
    Route::patch('/clients/{client}/restore', [ClientController::class, 'restore'])->middleware('permission:editClient');
    
    Route::apiResource('clients', ClientController::class)->middleware([
        'index' => 'permission:viewClient',
        'show' => 'permission:viewClient',
        'store' => 'permission:createClient',
        'update' => 'permission:editClient',
        'destroy' => 'permission:deleteClient',
    ]);
});

Route::get('/test-user', function () {
    $user = \App\Models\User::with('role.permissions')->first();

    return [
        'user' => $user,
        'hasCreateUserPermission' => $user?->hasPermission('createUser') ?? false,
    ];
});
