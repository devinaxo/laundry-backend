<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewUserRequest;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    public function store(NewUserRequest $request) {
        $validated = $request->validated();      
        $user = User::create($validated);
        return response()->json($user->load('role'), 201);
    }
    
    public function index() {
        return User::with('role')->get();
    }
}
