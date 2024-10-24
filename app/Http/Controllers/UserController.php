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
        $users = User::paginate(10);
        return response()->json([
            'message' => 'Users retrieved successfully',
            'users' => $users
        ], 200);
    }

    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

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
}
