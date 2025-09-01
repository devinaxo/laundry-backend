<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller {
    public function index() {
        return response()->json(Role::with('permissions')->get());
    }

    public function show(Role $role) {
        return response()->json($role->load('permissions'));
    }

    public function store(CreateRoleRequest $request) {
        $validated = $request->validated();

        $role = Role::create([
            'name' => $validated['name'],
            'displayName' => $validated['displayName']
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return response()->json($role->load('permissions'), 201);
    }

    public function update(UpdateRoleRequest $request, Role $role) {
        $validated = $request->validated();

        $role->update([
            'name' => $validated['name'] ?? $role->name,
            'displayName' => $validated['displayName'] ?? $role->displayName
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return response()->json($role->load('permissions'));
    }

    public function assignPermissions(Role $role, Request $request) {
        $request->validate([
            'permissions' => 'present|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->permissions()->sync($request->permissions);

        return response()->json([
            'message' => 'Permissions assigned successfully',
            'role' => $role->load('permissions')
        ]);
    }
}
