<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use App\User;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function roles()
    {
        try {
            $role = Role::all();
            $response = [
                'success' => true,
                'data' => $role,
                'message' => 'Successful role listing!'
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function permissions()
    {
        try {
            $permission = Permission::all();
            $response = [
                'success' => true,
                'data' => $permission,
                'message' => 'Successful permission listing!'
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function createRole(Request $request)
    {
        $validator  =   Validator::make(
            $request->all(),
            [
                'name' => 'string'
            ]
        );

        if ($validator->fails()) return response()->json(['success' => false, "messages" => $validator->errors()], 400);
        if (Role::where('name',  $request->name)->first()) return response()->json(['success' => false, 'message' => 'El rol ya existe!'], 401);

        $role = new Role([
            'guard_name' => 'api',
            'name' => $request->name
        ]);

        try {
            $role->save();
            $role->syncPermissions($request->permission_id, []);

            $response = [
                'success' => true,
                'data' => $role,
                'message' => 'Rol creado exitosamente!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }

    public function createPermission(Request $request)
    {
        $validator  =   Validator::make(
            $request->all(),
            [
                'name' => 'string'
            ]
        );

        if ($validator->fails()) return response()->json(['success' => false, "messages" => $validator->errors()], 400);
        if (Permission::where('name',  $request->name)->first()) return response()->json(['success' => false, 'message' => 'El permiso ya existe!'], 401);

        $permission = new Permission([
            'guard_name' => 'api',
            'name' => $request->name
        ]);

        try {
            $permission->save();

            $response = [
                'success' => true,
                'data' => $permission,
                'message' => 'Rol creado exitosamente!'
            ];

            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json('message: ' . $e->getMessage(), 500);
        }
    }
}
