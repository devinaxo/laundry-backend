<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
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

    /**
     * Category Endpoints
     */
    Route::apiResource('categories', CategoryController::class);

    /**
     * Subcategory Endpoints
     */
    Route::get('/categories/{category}/subcategories', [SubcategoryController::class, 'getByCategory']);
    Route::apiResource('subcategories', SubcategoryController::class);

    /**
     * Order Endpoints
     */
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::apiResource('orders', OrderController::class);

    /**
     * Order Items Endpoints (nested under orders)
     */
    Route::get('/orders/{order}/items', [OrderItemController::class, 'index']);
    Route::post('/orders/{order}/items', [OrderItemController::class, 'store']);
    Route::get('/orders/{order}/items/{orderItem}', [OrderItemController::class, 'show']);
    Route::put('/orders/{order}/items/{orderItem}', [OrderItemController::class, 'update']);
    Route::delete('/orders/{order}/items/{orderItem}', [OrderItemController::class, 'destroy']);
});