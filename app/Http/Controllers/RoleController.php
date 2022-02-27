<?php

namespace App\Http\Controllers;

use App\Models\Mitglieder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function attachRole(Request $request){
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'role_id' => 'required'
        ]);

        $mitglied = Mitglieder::find($fields['mitglied_id']);
        $role = Role::find($fields['role_id']);
        if(!$mitglied || !$role){
            return response([
                'success' => false,
               'error' => 'Falsche IDs!'
            ]);
        }
        if($mitglied->roles()->get()->contains($role)){
            return response([
                'success' => false,
                'error' => 'Rolle bereits vorhanden!'
            ]);
        }
        $mitglied->roles()->attach($role);

        $user = User::find($mitglied->user_id);
        $user->tokens()->delete();

        return response([
            'success' => $mitglied->roles()->get()->contains($role)
        ]);
    }

    public function detachRole(Request $request){
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'role_id' => 'required'
        ]);

        $mitglied = Mitglieder::find($fields['mitglied_id']);
        $role = Role::find($fields['role_id']);
        if(!$mitglied || !$role){
            return response([
                'success' => false,
                'error' => 'Falsche IDs!'
            ]);
        }
        if(!$mitglied->roles()->get()->contains($role)){
            return response([
                'success' => true,
                'error' => 'Rolle bereits entfernt!'
            ]);
        }
        $mitglied->roles()->detach($role);

        $user = User::find($mitglied->user_id);
        $user->tokens()->delete();

        return response([
            'success' => !$mitglied->roles()->get()->contains($role)
        ]);
    }

    public function getAll()
    {
        return Role::all();
    }

    public function create(Request $request)
    {
        $request->validate([
            'role' => 'required',
        ]);

        return Role::create($request->all());
    }

    public function getRolesForMitglied(Request $request)
    {
        $fields = $request->validate([
            'id' => 'required'
        ]);

        $mitglied = Mitglieder::where('user_id', $fields['id'])->first();
        if(!$mitglied){
            return response([
               'error' => 'Mitglied nicht gefunden oder kein User-Account genutzt!'
            ]);
        }
        return $mitglied->roles()->get();
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $role->update($request->all());
        return $role;
    }

    public function destroy($id)
    {
        Role::destroy($id);
    }

    public function search($name)
    {
        return Role::where('role', 'like', '%'.$name.'%')->get();
    }
}
