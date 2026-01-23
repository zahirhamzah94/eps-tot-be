<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user and return token.
     */
    public function register(RegisterRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            AuditService::logAuthAction(
                action: 'register',
                email: $request->email,
                success: false,
                reason: 'Validation failed',
                request: $request
            );
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        AuditService::logAuthAction(
            action: 'register',
            email: $user->email,
            username: $user->name,
            success: true,
            request: $request
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'type' => 'Bearer',
            'user' => new UserResource($user),
        ], 201);
    }

    /**
     * Handle user login and return token.
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            AuditService::logAuthAction(
                action: 'login',
                email: $request->email,
                success: false,
                reason: 'Invalid credentials',
                request: $request
            );
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        AuditService::logAuthAction(
            action: 'login',
            email: $user->email,
            username: $user->name,
            success: true,
            request: $request
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Handle user logout (current token).
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()?->delete();

            AuditService::logAuthAction(
                action: 'logout',
                email: $user->email,
                username: $user->name,
                success: true,
                request: $request
            );
        }

        return response()->json(['message' => 'Successfully logged out']);
    }
}
