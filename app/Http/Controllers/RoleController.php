<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json([
            'message' => 'Roles retrieved successfully',
            'roles' => $roles
        ], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validatedData['name']]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role
        ], 201);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->find($id);

        if ($role) {
            return response()->json([
                'message' => 'Role retrieved successfully',
                'role' => $role
            ], 200);
        } else {
            return response()->json(['message' => 'Role not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if ($role) {
            $validatedData = $request->validate([
                'name' => 'required|string|unique:roles,name,' . $role->id,
                'permissions' => 'sometimes|array',
                'permissions.*' => 'exists:permissions,name',
            ]);

            $role->update(['name' => $validatedData['name']]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return response()->json([
                'message' => 'Role updated successfully',
                'role' => $role
            ], 200);
        } else {
            return response()->json(['message' => 'Role not found'], 404);
        }
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if ($role) {
            $role->delete();
            return response()->json(['message' => 'Role deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Role not found'], 404);
        }
    }
}
