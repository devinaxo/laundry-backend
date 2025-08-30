<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Role;

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