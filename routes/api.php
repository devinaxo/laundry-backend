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
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:viewUser');
    Route::get('/users/all', [UserController::class, 'all'])->middleware('permission:viewUser');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('permission:viewUser');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:createUser');
    Route::patch('/users/{user}', [UserController::class, 'update'])->middleware('permission:editUser');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('permission:deleteUser');
    Route::patch('/users/{user}/restore', [UserController::class, 'restore'])->middleware('permission:editUser');

    /**
     * Role Endpoints
     */
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:viewRole');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->middleware('permission:viewRole');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:createRole');
    Route::patch('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:editRole');
    Route::patch('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])->middleware('permission:editRole');

    /**
     * Permission Endpoints
     */
    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:viewPermission');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->middleware('permission:viewPermission');
    Route::post('/permissions', [PermissionController::class, 'store'])->middleware('permission:createPermission');
    Route::patch('/permissions/{permission}', [PermissionController::class, 'update'])->middleware('permission:editPermission');

    /**
     * Client Endpoints
     */
    Route::get('/clients', [ClientController::class, 'index'])->middleware('permission:viewClient');
    Route::get('/clients/all', [ClientController::class, 'all'])->middleware('permission:viewClient');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->middleware('permission:viewClient');
    Route::post('/clients', [ClientController::class, 'store'])->middleware('permission:createClient');
    Route::patch('/clients/{client}', [ClientController::class, 'update'])->middleware('permission:editClient');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->middleware('permission:deleteClient');
    Route::patch('/clients/{client}/restore', [ClientController::class, 'restore'])->middleware('permission:editClient');
});

Route::get('/test-user', function () {
    $user = \App\Models\User::with('role.permissions')->first();

    return [
        'user' => $user,
        'hasCreateUserPermission' => $user?->hasPermission('createUser') ?? false,
    ];
});
