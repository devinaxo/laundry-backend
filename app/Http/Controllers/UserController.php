<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller {
    public function store(NewUserRequest $request) {
        $validated = $request->validated();
        $user = User::create($validated);
        return response()->json($user->load('role'), 201);
    }

    public function index() {
        return User::with('role')->where('active', true)->get();
    }

    public function all() {
        return User::with('role')->get();
    }

    public function show(User $user) {
        return response()->json($user->load('role'));
    }

    public function update(UpdateUserRequest $request, User $user) {
        $validated = $request->validated();

        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json($user->load('role'));
    }

    public function destroy(User $user) {
        $user->update(['active' => false]);

        return response()->json(['message' => 'User deactivated successfully']);
    }

    public function restore(User $user) {
        $user->update(['active' => true]);

        return response()->json(['message' => 'User reactivated successfully']);
    }
}
