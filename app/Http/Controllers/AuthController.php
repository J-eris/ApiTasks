<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'phone' => $validatedData['phone'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'birthday' => $validatedData['birthday'] ?? null,
                'account_status' => 'inactive',
                // 'verification_token' => Str::random(60),
            ]);

            // Send roles client to user
            if ($request->has('roles')) {
                $user->assignRole($request->roles);
            }

            if ($request->has('permissions')) {
                $user->givePermissionTo($request->permissions);
            }

            // Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user));

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

    // public function verifyEmail($token)
    // {
    //     $user = User::where('verification_token', $token)->firstOrFail();

    //     $user->verification_token = null;
    //     $user->account_status = 'active';
    //     $user->save();

    //     return response()->json([
    //         'status' => 'true',
    //         'message' => 'Email verified successfully!',
    //     ], 200);
    // }

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

    public function checkGoogleUser(Request $request)
    {
        $token = $request->input('token');

        $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($token);

        if ($payload) {
            $email = $payload['email'];
            $name = $payload['name'];

            $user = User::with('roles')->where('email', $email)->first();

            if ($user) {
                return response()->json([
                    'exists' => true,
                    'user' => $user
                ]);
            } else {
                return response()->json([
                    'exists' => false,
                    'email' => $email,
                    'name' => $name
                ]);
            }
        } else {
            return response()->json(['error' => 'Token inválido'], 400);
        }
    }

    public function loginWithGoogle(Request $request)
    {
        $token = $request->input('token');
        $roles = $request->input('roles');

        $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($token);

        if ($payload) {
            $email = $payload['email'];
            $name = $payload['name'];

            $user = User::where('email', $email)->first();

            if (!$user) {
                $password = uniqid();
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'account_status' => 'active',
                    // 'avatar' => $avatar,
                ]);
                $user->assignRole($roles ?? 'client');
            }

            Auth::login($user);

            return response()->json([
                'status' => 'true',
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ]);
        } else {
            return response()->json(['error' => 'Error al autenticar con Google'], 400);
        }
    }
}
