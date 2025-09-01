<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function login(LoginRequest $request) {
        $validated = $request->validated();
        if (!Auth::attempt($validated)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request) {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Logout successful']);
        }

        return response()->json(['error' => 'User not authenticated'], 401);
    }
}
