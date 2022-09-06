<?php

namespace App\Http\Controllers;

use App\Models\Mitglieder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:user_delete', ['only' => ['deleteUser']]);
    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'vorname' => 'required|string',
            'zuname' => 'required|string',
            'email' => 'required|string',
            'passwort' => 'required|string'
        ]);

        $mitglied = Mitglieder::where('email', $fields['email'])->firstOr(function () {
            abort(403, "E-Mail nicht in Datenbank vorhanden!");
        });

        if ($mitglied->user_id){
            abort(403, "User ist bereits registriert, bitte unter \"Login\" anmelden!");
        }

        if ($mitglied->vorname != $fields['vorname'] || $mitglied->zuname != $fields['zuname']){
            abort(403, "Falscher Name!");
        }

        $user = new User();
        $user->id = Str::uuid();
        $user->name = $fields['vorname'] . ' ' . $fields['zuname'];
        $user->email = $fields['email'];
        $user->passwort = bcrypt($fields['passwort']);

        $mitglied->user()->save($user);
        $mitglied->user_id = $user->id;
        $mitglied->save();

        if($mitglied->email == "rolandsams@gmail.com") {
            $user->assignRole('super-admin');
            $user->assignRole('Mitglied');
            $user->assignRole('Notenarchiv');
            $user->assignRole('Mitgliederverwaltung');
            $user->assignRole('Terminverwaltung');
            $user->assignRole('Anwesenheits/Stück-Erfassung');
            $user->assignRole('Administration');
        }
        else{
            $user->assignRole('Mitglied');
        }

        return response([
            'message' => 'Registrierung erfolgreich!'
        ], 201);
    }

    public function testGulaschCloudLogin(Request $request){
        return response(null, 200)->header('WWW-Authenticate','Basic realm="REALM"');
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'passwort' => 'required|string'
        ]);

        //Check email
        $user = User::where('email', $fields['email'])->firstOr(function () {
            abort(403, "E-Mail nicht gefunden!");
        });

        //Check password
        if (!Hash::check($fields['passwort'], $user->passwort)) {
            abort(403, "Falsches Passwort!");
        }

        $mitglied = Mitglieder::where('user_id', $user->id)->first();
        $token = $user->createToken('mkjToken')->plainTextToken;

        $response = [
            'user' => $user,
            'mitglied' => $mitglied,
            'roles' => $user->roles()->get(),
            'permissions' => $user->getAllPermissions(),
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
            'roles' => $user->roles()->get(),
            'permissions' => $user->getAllPermissions(),
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'success' => true
        ];
    }

    public function deleteUser(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required'
        ]);
        $user = User::where('email', $fields['email'])->first();
        $user->tokens()->delete();
        User::destroy($user->id);
        return [
            'success' => true
        ];
    }
}
