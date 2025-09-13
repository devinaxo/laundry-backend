<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permissions\CreatePermissionRequest;
use App\Http\Requests\Permissions\UpdatePermissionRequest;
use App\Models\Permission;

class PermissionController extends Controller {
    public function index() {
        return response()->json(Permission::all());
    }

    public function show(Permission $permission) {
        return response()->json($permission);
    }

    public function store(CreatePermissionRequest $request) {
        $validated = $request->validated();

        $permission = Permission::create($validated);

        return response()->json($permission, 201);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission) {
        $validated = $request->validated();

        $permission->update($validated);

        return response()->json($permission);
    }
}
