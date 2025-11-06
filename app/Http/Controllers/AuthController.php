<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

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

    /**
     * Send password reset link to user's email
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $validated = $request->validated();
        
        // Find user by email
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'No se encontró un usuario con ese correo electrónico'
            ], 404);
        }

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $validated['email'],
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        $user->notify(new ResetPasswordNotification($token, $validated['email']));

        return response()->json([
            'message' => 'Se ha enviado un enlace para restablecer tu contraseña a tu correo electrónico'
        ], 200);
    }

    /**
     * Reset user password
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validated();

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'Token de restablecimiento inválido o expirado'
            ], 400);
        }

        if (!Hash::check($validated['token'], $passwordReset->token)) {
            return response()->json([
                'message' => 'Token de restablecimiento inválido'
            ], 400);
        }

        $tokenCreatedAt = Carbon::parse($passwordReset->created_at);
        $expireMinutes = config('auth.passwords.users.expire', 60);
        
        if ($tokenCreatedAt->addMinutes($expireMinutes)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
            return response()->json([
                'message' => 'El token de restablecimiento ha expirado'
            ], 400);
        }

        $user = User::where('email', $validated['email'])->first();
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Tu contraseña ha sido restablecida exitosamente'
        ], 200);
    }
}
