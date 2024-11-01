<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validatedData = $request->validated();;

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'phone' => $validatedData['phone'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'birthday' => $validatedData['birthday'] ?? null,
                'role' => $validatedData['role'] ?? 'client',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully!',
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to register user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'The provided credentials are incorrect.',
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            return response()->json([
                'status' => 'true',
                'message' => 'User logged in successfully!',
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => 'An error occurred during login.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'true',
            'message' => 'User logged out successfully!',
        ], 200);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()->pluck('name');

        return response()->json([
            'status' => 'true',
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        $user = $request->user();

        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($validatedData['new_password']);
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }
}
