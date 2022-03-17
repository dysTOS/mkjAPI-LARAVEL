<?php

namespace App\Http\Controllers;

use App\Models\Mitglieder;
use App\Models\Role;
use App\Models\Token;
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
            abort(403, "IDs falsch!");
        }
        if($mitglied->roles()->get()->contains($role)){
            abort(403, "Mitglied hat Rolle bereits erhalten.");
        }
        $mitglied->roles()->attach($role);

        $user = User::find($mitglied->user_id);
        if($user) {
            $user->tokens()->delete();
        }

        return response([
            'success' => $mitglied->roles()->get()->contains($role),
            'message' => 'Rolle '.$role->role.' zugewiesen!'
        ], 200);
    }

    public function detachRole(Request $request){
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'role_id' => 'required'
        ]);

        $mitglied = Mitglieder::find($fields['mitglied_id']);
        $role = Role::find($fields['role_id']);
        if(!$mitglied || !$role){
            abort(403, "IDs falsch!");
        }
        if(!$mitglied->roles()->get()->contains($role)){
            abort(403, "Rolle nicht vorhanden!");
        }
        $mitglied->roles()->detach($role);

        $user = User::find($mitglied->user_id);
        if($user) {
            $user->tokens()->delete();
        }

        return response([
            'success' => !$mitglied->roles()->get()->contains($role),
            'message' => 'Rolle '. $role->role.' entfernt!'
        ], 200);
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

        $mitglied = Mitglieder::find($fields['id']);
        if(!$mitglied){
            abort(403,
               'Kein Mitglied gefunden!'
            );
        }
        return $mitglied->roles()->get();
    }

    public function getRolesForUser(Request $request)
    {
        $fields = $request->validate([
            'id' => 'required'
        ]);

        $mitglied = Mitglieder::where('user_id', $fields['id'])->first();
        if(!$mitglied){
            abort(403,
                'Kein User gefunden!'
            );
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
}
