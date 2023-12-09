<?php

namespace App\Http\Controllers;


use App\Configurations\PermissionMap;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

class RoleController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:' . PermissionMap::ROLE_READ, ['only' => ['getAllRoles', 'getAllPermissions', 'getPermissionsForRole']]);
        $this->middleware('permission:' . PermissionMap::ROLE_SAVE, ['only' => ['createRole', 'updateRole']]);
        $this->middleware('permission:' . PermissionMap::ROLE_DELETE, ['only' => ['deleteRole']]);
        $this->middleware('permission:' . PermissionMap::ROLE_ASSIGN, ['only' => ['assignRolesToUser']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Role[]
     */
    public function getAllRoles()
    {
        $roles = Role::all()->filter(function ($role) {
            return $role->name != 'super-admin';
        })->sortBy('name')->values();
        return $roles;
    }

    public function getAllPermissions()
    {
        return Permission::all()->sortBy('name')->values();
    }

    public function getUserRoles($id)
    {
        $user = User::where('id', $id)->first();
        return $user->roles()->get();
    }

    public function getUserPermissions($id)
    {
        $user = User::where('id', $id)->first();
        return $user->getAllPermissions();
    }

    public function assignRolesToUser(Request $request, $id)
    {
        $this->validate($request, [
            'roles' => 'required',
        ]);
        $user = User::where('id', $id)->first();
        if (!$user) {
            abort(300, 'Kein User-Account gefunden! Das Mitglied muss sich zuerst registrieren!');
        }
        $user->syncRoles($request->input('roles'));
        return $user->roles()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createRole(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name'), 'guard_name' => 'web']);
        $role->syncPermissions($request->input('permission'));

        return $role;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return $role;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getPermissionsForRole($id)
    {
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return $rolePermissions;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteRole($id)
    {
        return Role::destroy($id);

    }
}
