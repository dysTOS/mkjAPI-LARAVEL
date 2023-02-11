<?php

namespace App\Http\Controllers;

use App\Models\Ausrueckung;
use Illuminate\Http\Request;
use App\Models\Mitglieder;
use App\Models\User;
use App\Models\Gruppe;
use App\Constants\PermissionMap;

class MitgliederController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'. PermissionMap::MITGLIEDER_READ, ['only' => ['getAll','getAllActive', 'getMitgliederOfAusrueckung', 'search', 'updateOwnMitgliedData']]);
        $this->middleware('permission:'. PermissionMap::MITGLIEDER_SAVE, ['only' => ['create', 'update', 'getSingle']]);
        $this->middleware('permission:'. PermissionMap::MITGLIEDER_DELETE, ['only' => ['destroy']]);
        $this->middleware('permission:'. PermissionMap::MITGLIEDER_ASSIGN, ['only' => ['attachMitgliedToAusrueckung', 'detachMitgliedFromAusrueckung','attachMitgliedToGruppe', 'detachMitgliedFromGruppe']]);
    }

    public function getAll()
    {
        $mitglieder = Mitglieder::query()->orderBy('zuname', 'asc')->get();
        return $mitglieder->load('gruppen');
    }

    public function getAllActive()
    {
        $mitglieder =  Mitglieder::where('aktiv', true)->orderBy('zuname', 'asc')->get();
        return $mitglieder->load('gruppen');
    }

    public function getMitgliederOfAusrueckung($id){
        $ausrueckung = Ausrueckung::find($id);
        $mitglieder = $ausrueckung->mitglieder()->get();
        return $mitglieder;
    }

    public function create(Request $request)
    {
        $request->validate([
            'vorname' => 'required',
            'zuname' => 'required',
        ]);

        return Mitglieder::create($request->all());
    }

    public function getSingle($id)
    {
        return Mitglieder::find($id);
    }

    public function update(Request $request, $id)
    {
        $mitglied = Mitglieder::find($id);
        $mitglied->update($request->all());
        return $mitglied;
    }

    public function updateOwnMitgliedData(Request $request)
    {
        $fields = $request->validate([
            'id' => 'required',
            'vorname' => 'required',
            'zuname' => 'required',
            'email' => 'required'
        ]);

        $user = $request->user();
        $mitglied = Mitglieder::where('id', '=', $user->mitglied_id)->first();

        if($mitglied->id != $fields['id'])
        {
            abort(300, 'Keine Berechtigung!');
        }

        $mitglied->update(array(
            'vorname' => $request['vorname'],
            'zuname' => $request['zuname'],
            'titelVor' => $request['titelVor'],
            'titelNach' => $request['titelNach'],
            'geburtsdatum' => $request['geburtsdatum'],
            'geschlecht' => $request['geschlecht'],
            'strasse' => $request['strasse'],
            'hausnummer' => $request['hausnummer'],
            'ort' => $request['ort'],
            'plz' => $request['plz'],
            'telefonHaupt' => $request['telefonHaupt'],
            'telefonMobil' => $request['telefonMobil'],
            'email' => $request['email'],
            'beruf' => $request['beruf'],
        ));

        return $mitglied;
    }

    public function destroy($id)
    {
        $mitglied = Mitglieder::findOrFail($id);
        $user = User::find($mitglied->user_id);

        if($user){
            $user->tokens()->delete();
            User::destroy($user->id);
        }

        Mitglieder::destroy($id);
    }

    public function search($name)
    {
        return Mitglieder::where('zuname', 'like', '%'.$name.'%')
            ->orWhere('vorname', 'like', '%'.$name.'%')->get();
    }

    public function attachMitgliedToAusrueckung(Request $request){
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'ausrueckung_id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($fields['mitglied_id']);
        $ausrueckung = Ausrueckung::findOrFail($fields['ausrueckung_id']);
        $ausrueckung->mitglieder()->attach($mitglied);

        return response([
            'success' => $ausrueckung->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied '.$mitglied->vorname.' '.$mitglied->zuname.' zugewiesen!'
        ], 200);
    }

    public function detachMitgliedFromAusrueckung(Request $request){
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'ausrueckung_id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($fields['mitglied_id']);
        $ausrueckung = Ausrueckung::findOrFail($fields['ausrueckung_id']);
        $ausrueckung->mitglieder()->detach($mitglied);

        return response([
            'success' => !$ausrueckung->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied '.$mitglied->vorname.' '.$mitglied->zuname.' entfernt!'
        ], 200);
    }

    public function attachMitgliedToGruppe(Request $request){
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'gruppe_id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($fields['mitglied_id']);
        $gruppe = Gruppe::findOrFail($fields['gruppe_id']);
        $gruppe->mitglieder()->attach($mitglied);

        return response([
            'success' => $gruppe->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied '.$mitglied->vorname.' '.$mitglied->zuname.' nach '. $gruppe->name.' zugewiesen!'
        ], 200);
    }
    public function detachMitgliedFromGruppe(Request $request){
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'gruppe_id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($fields['mitglied_id']);
        $gruppe = Gruppe::findOrFail($fields['gruppe_id']);
        $gruppe->mitglieder()->detach($mitglied);

        return response([
            'success' => !$gruppe->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied '.$mitglied->vorname.' '.$mitglied->zuname.' von '. $gruppe->name.' entfernt!'
        ], 200);
    }
}
