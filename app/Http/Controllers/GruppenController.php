<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\DAO\ListQueryDAO;
use App\Models\Gruppe;
use App\Models\Mitglieder;
use Illuminate\Http\Request;

class GruppenController extends Controller implements _CrudControllerInterface
{
    function __construct()
    {
        $this->middleware('permission:' . PermissionMap::GRUPPEN_READ, ['only' => ['getList', 'getById', 'getGruppenLeiter', 'getMitgliederOfGruppe', 'getGruppenOfMitglied']]);
        $this->middleware('permission:' . PermissionMap::GRUPPEN_SAVE, ['only' => ['create', 'update']]);
        $this->middleware('permission:' . PermissionMap::GRUPPEN_DELETE, ['only' => ['delete']]);
        $this->middleware('permission:' . PermissionMap::GRUPPEN_ASSIGN, ['only' => ['addMitgliedToGruppe', 'removeMitgliedFromGruppe']]);
    }

    public function getList(Request $request)
    {
        $handler = new ListQueryDAO(Gruppe::class, array('load' => array('mitglieder', 'gruppenleiter')));
        $output = $handler->getListOutput($request);
        return response($output, 200);
    }

    public function getById(Request $request, $id)
    {
        return Gruppe::findOrFail($request->id)->load('gruppenleiter');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        return Gruppe::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $existentGruppe = Gruppe::findOrFail($request['id']);
        return $existentGruppe->update($request->all());

    }

    public function delete(Request $request, $id)
    {
        return Gruppe::destroy($id);
    }

    public static function addMitgliedToGruppe(Request $request)
    {
        $fields = $request->validate([
            'gruppe_id' => 'required',
            'mitglied_id' => 'required',
        ]);

        $mitglied = Mitglieder::findOrFail($fields['mitglied_id']);
        $gruppe = Gruppe::findOrFail($fields['gruppe_id']);
        if($gruppe->mitglieder()->get()->contains($mitglied)){
            abort(500, "Mitglied bereits zugewiesen!");
        }
        $gruppe->mitglieder()->attach($mitglied);

        return response([
            'success' => $gruppe->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied ' . $mitglied->vorname . ' ' . $mitglied->zuname . ' zugewiesen!'
        ], 200);
    }

    public static function removeMitgliedFromGruppe(Request $request)
    {
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'gruppe_id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($fields['mitglied_id']);
        $gruppe = Gruppe::findOrFail($fields['gruppe_id']);
        $gruppe->mitglieder()->detach($mitglied);

        return response([
            'success' => !$gruppe->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied ' . $mitglied->vorname . ' ' . $mitglied->zuname . ' entfernt!'
        ], 200);
    }


    public static function getGruppenLeiter(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $gruppe = Gruppe::findOrFail($request['gruppe_id']);


        return $gruppe->gruppenleiter()->get();
    }

    public static function getMitgliederOfGruppe(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $gruppe = Gruppe::findOrFail($request['id']);

        return $gruppe->mitglieder()->get();
    }

    public static function getGruppenOfMitglied(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($request['id']);

        return $mitglied->gruppen()->get();
    }
}
