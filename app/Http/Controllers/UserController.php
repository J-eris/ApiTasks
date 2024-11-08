<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return response()->json([
            'message' => 'Users retrieved successfully',
            'users' => $users
        ], 200);
    }

    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        if ($request->has('permissions')) {
            $user->givePermissionTo($request->permissions);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // Obtener un usuario por ID
    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'message' => 'User retrieved successfully',
                'user' => $user,
            ], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    // Actualizar un usuario
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $validatedData = $request->validated();
            $user->update($validatedData);

            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            if ($request->has('permissions')) {
                $user->syncPermissions($request->permissions);
            }

            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user,
            ], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    // Inactive account user
    public function updateStatus(UserRequest $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $validatedData = $request->validated();
            $user->account_status = $validatedData['account_status'];
            $user->save();

            $message = $user->account_status === 'active' ? 'User activated' : 'User deactivated';

            return response()->json(['message' => $message, 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted'], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    // Verificar si un usuario tiene un rol específico
    public function checkRole(Request $request)
    {
        $user = User::user();

        if ($user->hasRole($request->role)) {
            return response()->json(['message' => 'The user has the role'], 200);
        } else {
            return response()->json(['message' => 'The user does not have the role'], 404);
        }
    }
}
