<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller {
    public function login(LoginRequest $request) {
        PersonalAccessToken::where('expires_at', '<', now())->delete();

        
        $validated = $request->validated();

        $credentials = array_merge($validated, ['active' => true]);
        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials or account is inactive'], 401);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();
        // $token = $user->createToken('auth_token')->plainTextToken;
        $token = $user->createToken('auth_token', ['*'], now()->addMinutes(480))->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            //bring role and permissions
            'user' => $user->load('role.permissions')
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

    public function user(Request $request) {
        $user = $request->user();

        return response()->json($user->load('role.permissions'));
    }
}
