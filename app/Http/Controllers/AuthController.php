<?php

namespace App\Http\Controllers;

use App\Models\Mitglieder;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

        if ($mitglied->user_id) {
            abort(403, "User ist bereits registriert, bitte unter \"Login\" anmelden!");
        }

        if($mitglied->aktiv != true){
            abort(403, "Dein Account muss erst aktiviviert werden! Bitte kontaktiere deinen Administrator!");
        }

        if ($mitglied->vorname != $fields['vorname'] || $mitglied->zuname != $fields['zuname']) {
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

        if ($mitglied->email == "rolandsams@gmail.com") {
            $user->assignRole('super-admin');
            $user->assignRole('Mitglied');
            $user->assignRole('Notenarchiv');
            $user->assignRole('Mitgliederverwaltung');
            $user->assignRole('Terminverwaltung');
            $user->assignRole('Anwesenheits/Stück-Erfassung');
            $user->assignRole('Administration');
        } else {
            $user->assignRole('Mitglied');
        }

        return response([
            'message' => 'Registrierung erfolgreich!'
        ], 201);
    }

    public function testGulaschCloudLogin(Request $request)
    {
        return response(null, 403)->header('WWW-Authenticate', 'Basic realm="REALM"');
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
            abort(403, "Zugangsdaten inkorrekt!");
        }

        $mitglied = Mitglieder::where('user_id', $user->id)->first();

        if($mitglied->aktiv != true){
            abort(403, "Dein Account wurde deaktiviert! Bitte kontaktiere deinen Administrator!");
        }

        $token = $user->createToken('mkjToken')->plainTextToken;

        $response = [
            'user' => $user,
            'mitglied' => $mitglied,
            'roles' => $user->roles()->get(),
            'permissions' => $user->getAllPermissions(),
            'gruppen' => $mitglied->gruppen(),
            'token' => $token
        ];

        return response($response, 201);
    }

    public function getCurrentUser(Request $request)
    {
        $user = $request->user();
        $mitglied = Mitglieder::where('user_id', $user->id)->first();

        return response([
            'user' => $user,
            'mitglied' => $mitglied,
            'roles' => $user->roles()->get(),
            'permissions' => $user->getAllPermissions(),
            'gruppen' => $mitglied->gruppen(),
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response([
            'success' => true
        ]);
    }

    public function deleteUser(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required'
        ]);
        $user = User::where('email', $fields['email'])->first();
        $user->tokens()->delete();
        User::destroy($user->id);
        return response([
            'success' => true
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user){
            abort(404, 'E-Mail nicht gefunden! Entweder falsch eingegeben oder nicht registriert!');
        }

        $user->sendPasswordResetNotification(
            Password::createToken($user)
        );
        return response([
            'message' => 'Ein Link zum Zurücksetzen deines Passworts wurde an deine E-Mail Adresse gesendet!'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $fields = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            // 'password' => 'required|min:8|confirmed',
            'password' => 'required',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user){
            abort(404, 'Reset-Link ungültig!');
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'passwort' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? response([
                        'message' => 'Passwort wurde gesetzt!'
                    ], 200)
                    : abort(500, 'Fehler beim Zurücksetzen des Passworts!');
    }
}
