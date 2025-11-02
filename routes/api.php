<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

// Auth Endpoints
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/currentUser', [AuthController::class, 'user']);
});

Route::middleware('auth:sanctum')->group(function () {

    /**
     * Dashboard Endpoints
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:viewOrders');
    Route::get('/dashboard/orders-overview', [DashboardController::class, 'ordersOverview'])->middleware('permission:viewOrders');
    Route::get('/dashboard/weekly-revenue', [DashboardController::class, 'weeklyRevenue'])->middleware('permission:viewOrders');

    /**
     * User Endpoints
     */
    Route::get('/users/all', [UserController::class, 'all'])->middleware('permission:viewUsers');
    Route::patch('/users/{user}/restore', [UserController::class, 'restore'])->middleware('permission:editUsers');
    Route::apiResource('users', UserController::class)->middleware([
        'index' => 'permission:viewUsers',
        'show' => 'permission:viewUsers',
        'store' => 'permission:createUsers',
        'update' => 'permission:editUsers',
        'destroy' => 'permission:deleteUsers',
    ]);

    /**
     * Role Endpoints
     */
    Route::patch('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])->middleware('permission:editRoles');
    Route::apiResource('roles', RoleController::class)->middleware([
        'index' => 'permission:viewRoles',
        'show' => 'permission:viewRoles',
        'update' => 'permission:editRoles',
    ]);

    /**
     * Permission Endpoints
     */
    Route::apiResource('permissions', PermissionController::class)->middleware([
        'index' => 'permission:viewRoles',
        'show' => 'permission:viewRoles',
    ]);

    /**
     * Client Endpoints
     */
    Route::get('/clients/all', [ClientController::class, 'all'])->middleware('permission:viewClients');
    Route::get('/clients/paginated', [ClientController::class, 'paginated'])->middleware('permission:viewClients');
    Route::patch('/clients/{client}/restore', [ClientController::class, 'restore'])->middleware('permission:editClients');
    
    Route::apiResource('clients', ClientController::class)->middleware([
        'index' => 'permission:viewClients',
        'show' => 'permission:viewClients',
        'store' => 'permission:createClients',
        'update' => 'permission:editClients',
        'destroy' => 'permission:deleteClients',
    ]);

    /**
     * Category Endpoints
     */
    Route::apiResource('categories', CategoryController::class)->middleware([
        'index' => 'permission:viewCategories',
        'show' => 'permission:viewCategories',
        'store' => 'permission:createCategories',
        'update' => 'permission:editCategories',
        'destroy' => 'permission:deleteCategories',
    ]);

    /**
     * Subcategory Endpoints
     */
    Route::get('/categories/{category}/subcategories', [SubcategoryController::class, 'getByCategory'])->middleware(['permission:viewCategories', 'permission:viewSubcategories']);
    Route::apiResource('subcategories', SubcategoryController::class)->middleware([
        'index' => 'permission:viewSubcategories',
        'show' => 'permission:viewSubcategories',
        'store' => 'permission:createSubcategories',
        'update' => 'permission:editSubcategories',
        'destroy' => 'permission:deleteSubcategories',
    ]);

    /**
     * Order Endpoints
     */
    Route::get('/orders/recent', [OrderController::class, 'recent'])->middleware('permission:viewOrders');
    Route::get('/orders/paginated', [OrderController::class, 'paginated'])->middleware('permission:viewOrders');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->middleware('permission:editOrders');
    Route::put('/orders/{order}/replace', [OrderController::class, 'replace'])->middleware('permission:editOrders');
    Route::apiResource('orders', OrderController::class)->middleware([
        'index' => 'permission:viewOrders',
        'show' => 'permission:viewOrders',
        'store' => 'permission:createOrders',
        'update' => 'permission:editOrders',
        'destroy' => 'permission:deleteOrders',
    ]);

    /**
     * Order Items Endpoints (nested under orders)
     */
    Route::get('/orders/{order}/items', [OrderItemController::class, 'index'])->middleware('permission:viewOrders');
    Route::post('/orders/{order}/items', [OrderItemController::class, 'store'])->middleware('permission:editOrders');
    Route::get('/orders/{order}/items/{orderItem}', [OrderItemController::class, 'show'])->middleware('permission:viewOrders');
    Route::put('/orders/{order}/items/{orderItem}', [OrderItemController::class, 'update'])->middleware('permission:editOrders');
    Route::delete('/orders/{order}/items/{orderItem}', [OrderItemController::class, 'destroy'])->middleware('permission:editOrders');

    /**
     * Analytics Endpoints (for admin graphs and statistics)
     */
    Route::prefix('analytics')->middleware('permission:viewOrders')->group(function () {
        Route::get('/overview', [AnalyticsController::class, 'overview']);
        Route::get('/monthly-stats', [AnalyticsController::class, 'monthlyStats']);
        Route::get('/orders-per-month', [AnalyticsController::class, 'ordersPerMonth']);
        Route::get('/yearly-comparison', [AnalyticsController::class, 'yearlyComparison']);
        Route::get('/daily-stats', [AnalyticsController::class, 'dailyStats']);
        Route::get('/top-clients', [AnalyticsController::class, 'topClients']);
        Route::get('/frequent-clients', [AnalyticsController::class, 'mostFrequentClients']);
        Route::get('/popular-services', [AnalyticsController::class, 'popularServices']);
        Route::get('/category-revenue', [AnalyticsController::class, 'categoryRevenue']);
        Route::get('/status-distribution', [AnalyticsController::class, 'statusDistribution']);
    });
});