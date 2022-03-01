<?php

namespace App\Http\Controllers;

use App\Models\Mitglieder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'vorname' => 'required|string',
            'zuname' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'passwort' => 'required|string'
        ]);

        $mitglied = Mitglieder::where('email', $fields['email'])->first();

        if (!$mitglied || $mitglied->vorname != $fields['vorname'] || $mitglied->zuname != $fields['zuname']) {
            /*return response([
                'message' => 'Falsche Zugangsdaten!'
            ], 401);*/
            throw ValidationException::withMessages(['Falsche Zugangsdaten']);

        }

        if($mitglied->email == "rolandsams@gmail.com"){
            $standardRole = Role::where('role', '=', 'mitglied')->first();
            $mitglied->roles()->attach($standardRole);
            $adminRole = Role::where('role', '=', 'admin')->get();
            $mitglied->roles()->attach($adminRole);
        }
        if($mitglied->email == "viktoriasams@gmail.com"){
            $standardRole = Role::where('role', '=', 'mitglied')->first();
            $mitglied->roles()->attach($standardRole);
            $adminRole = Role::where('role', '=', 'ausschuss')->get();
            $mitglied->roles()->attach($adminRole);
        }

        $roles = $mitglied->roles()->get();
        if (count($roles) == 0) {
            $standardRole = Role::where('role', '=', 'mitglied')->first();
            $mitglied->roles()->attach($standardRole);
        }

        $user = new User();
        $user->name = $fields['vorname'] . ' ' . $fields['zuname'];
        $user->email = $fields['email'];
        $user->passwort = bcrypt($fields['passwort']);

        $mitglied->user()->save($user);
        $mitglied->user_id = $user->id;
        $mitglied->save();

        return response([
            'message' => 'Registrierung erfolgreich!'
        ], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'passwort' => 'required|string'
        ]);

        //Check email
        $user = User::where('email', $fields['email'])->first();

        //Check password
        if (!$user || !Hash::check($fields['passwort'], $user->passwort)) {
            return response([
                'message' => 'Login-Daten falsch!'
            ], 401);
        }

        $mitglied = Mitglieder::where('user_id', $user->id)->first();

        $roleStringArray = $this->getRoleStringForMitglied($mitglied);
        if (count($roleStringArray)) {
            $token = $user->createToken('mkjToken', $roleStringArray)->plainTextToken;
        } else {
            $token = $user->createToken('mkjToken')->plainTextToken;
        }

        $response = [
            'user' => $user,
            'mitglied' => $mitglied,
            'roles' => $mitglied->roles()->get(),
            'token' => $token
        ];

        return response($response, 201);
    }

    public function getCurrentUser(Request $request){
        $user = $request->user();
        $mitglied = Mitglieder::where('user_id', $user->id)->first();

        return response([
            'user' => $user,
            'mitglied' => $mitglied,
            'roles' => $mitglied->roles()->get(),
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'Erfolgreich abgemeldet!'
        ];
    }

    public function deleteUser(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required'
        ]);
        $user = User::where('email', $fields['email'])->first();
        $request->user()->tokens()->delete();
        User::destroy($user->id);
        return [
            'message' => 'User successfully deleted!'
        ];
    }

    private function getRoleStringForMitglied(Mitglieder $mitglied)
    {
        $roleStringArray = [];
        foreach ($mitglied->roles as $role) {
            $roleStringArray[] = $role->role;
        }
        return $roleStringArray;
    }
}
