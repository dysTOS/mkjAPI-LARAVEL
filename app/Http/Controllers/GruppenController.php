<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitglieder;
use App\Models\Gruppe;
use App\Constants\PermissionMap;

class GruppenController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'. PermissionMap::GRUPPEN_READ, ['only' => ['getAllGruppen','getGruppeById', 'getGruppenLeiter','getMitgliederOfGruppe','getGruppenOfMitglied']]);
        $this->middleware('permission:'. PermissionMap::GRUPPEN_SAVE, ['only' => ['saveGruppe']]);
        $this->middleware('permission:'. PermissionMap::GRUPPEN_DELETE, ['only' => ['deleteGruppe']]);
        $this->middleware('permission:'. PermissionMap::GRUPPEN_ASSIGN, ['only' => ['addMitgliedToGruppe', 'removeMitgliedFromGruppe']]);
    }

    public static function addMitgliedToGruppe(Request $request){
        $fields = $request->validate([
            'gruppe_id' => 'required',
            'mitglied_id' => 'required',
        ]);

        $mitglied = Mitglieder::findOrFail($fields['mitglied_id']);
        $gruppe = Gruppe::findOrFail($fields['gruppe_id']);
        $gruppe->mitglieder()->attach($mitglied);

        return response([
            'success' => $gruppe->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied '.$mitglied->vorname.' '.$mitglied->zuname.' zugewiesen!'
        ], 200);
    }

    public static function removeMitgliedFromGruppe(Request $request){
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'gruppe_id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($fields['mitglied_id']);
        $gruppe = Gruppe::findOrFail($fields['gruppe_id']);
        $gruppe->mitglieder()->detach($mitglied);

        return response([
            'success' => !$gruppe->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied '.$mitglied->vorname.' '.$mitglied->zuname.' entfernt!'
        ], 200);
    }

    public static function getAllGruppen(Request $request)
    {
        $gruppen = Gruppe::all();

        if($request['includeMitglieder']){
            $gruppen->load('mitglieder');
        }
        if($request['includeGruppenleiter']){
            $gruppen->load('gruppenleiter');
        }

        return $gruppen->load('ausrueckungen');
    }

    public static function getGruppe(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        return Gruppe::find($request->id);
    }

    public static function saveGruppe(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $existentGruppe = null;

        if($request['id']){
            $existentGruppe = Gruppe::find($request['id']);
        }

        if($existentGruppe == null){
            $existentGruppe = Gruppe::where('name', '=', $request['name'])->first();
        }

        if($existentGruppe){
            $existentGruppe->update($request->all());
            return $existentGruppe;
        }

        return Gruppe::create($request->all());
    }

    public static function deleteGruppe(Request $request, $id)
    {
        return Gruppe::destroy($id);
    }

    public static function getGruppenLeiter(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        $gruppe = Gruppe::findOrFail($request['gruppe_id']);


        return $gruppe->gruppenleiter()->get();
    }

    public static function getMitgliederOfGruppe(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        $gruppe = Gruppe::findOrFail($request['id']);

        return $gruppe->mitglieder()->get();
    }

    public static function getGruppenOfMitglied(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($request['id']);

        return $mitglied->gruppen()->get();
    }
}
