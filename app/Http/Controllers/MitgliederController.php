<?php

namespace App\Http\Controllers;

use App\DAO\ListQueryDAO;
use App\Models\Termin;
use Illuminate\Http\Request;
use App\Models\Mitglieder;
use App\Models\User;
use App\Models\Gruppe;
use App\Configurations\PermissionMap;
use Illuminate\Support\Carbon;

class MitgliederController extends Controller implements _CrudControllerInterface
{
    function __construct()
    {
        $this->middleware('permission:' . PermissionMap::MITGLIEDER_READ, ['only' => ['getList', 'getMitgliederOfAusrueckung', 'search', 'updateOwnMitgliedData']]);
        $this->middleware('permission:' . PermissionMap::MITGLIEDER_SAVE, ['only' => ['create', 'update', 'getById']]);
        $this->middleware('permission:' . PermissionMap::MITGLIEDER_DELETE, ['only' => ['delete']]);
        $this->middleware('permission:' . PermissionMap::MITGLIEDER_ASSIGN, ['only' => ['attachMitgliedToAusrueckung', 'detachMitgliedFromAusrueckung', 'attachMitgliedToGruppe', 'detachMitgliedFromGruppe']]);
    }

    public function getList(Request $request)
    {
        $handler = new ListQueryDAO(Mitglieder::class, array('load' => 'gruppen'));
        $output = $handler->getListOutput($request);
        return response($output, 200);
    }

    public function getMitgliederOfAusrueckung($id)
    {
        $ausrueckung = Termin::find($id);
        $mitglieder = $ausrueckung->mitglieder()->get();
        return $mitglieder;
    }

    public function getById(Request $request, $id)
    {
        return Mitglieder::findOrFail($id);
    }

    public function delete(Request $request, $id)
    {
        $mitglied = Mitglieder::findOrFail($id);
        $user = User::findOrFail($mitglied->user_id);

        if ($user) {
            $user->tokens()->delete();
            User::destroy($user->id);
        }

        Mitglieder::destroy($id);
    }

    public function create(Request $request)
    {
        $request->validate([
            'vorname' => 'required',
            'zuname' => 'required',
        ]);

        return Mitglieder::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $mitglied = Mitglieder::find($id);
        $mitglied->update($request->all());
        return $mitglied;
    }

    public function getNextGeburtstage(Request $request)
    {
        $date = now();
        return Mitglieder::where(function ($query) {
            $query->where('aktiv', true);
        })
            ->where(function ($query) use ($date) {
                $query->whereMonth('geburtsdatum', '>', $date->month)
                    ->orWhere(function ($query) use ($date) {
                        $query->whereMonth('geburtsdatum', '=', $date->month)
                            ->whereDay('geburtsdatum', '>=', $date->day);
                    });
            })
            ->orderByRaw("SUBSTRING(geburtsdatum, 6, 5)")
            ->take(3)
            ->get();
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

        if ($mitglied->id != $fields['id']) {
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

    public function search($name)
    {
        return Mitglieder::where('zuname', 'like', '%' . $name . '%')
            ->orWhere('vorname', 'like', '%' . $name . '%')->get();
    }

    public function attachMitgliedToAusrueckung(Request $request)
    {
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'ausrueckung_id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($fields['mitglied_id']);
        $ausrueckung = Termin::findOrFail($fields['ausrueckung_id']);
        $ausrueckung->mitglieder()->attach($mitglied);

        return response([
            'success' => $ausrueckung->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied ' . $mitglied->vorname . ' ' . $mitglied->zuname . ' zugewiesen!'
        ], 200);
    }

    public function detachMitgliedFromAusrueckung(Request $request)
    {
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'ausrueckung_id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($fields['mitglied_id']);
        $ausrueckung = Termin::findOrFail($fields['ausrueckung_id']);
        $ausrueckung->mitglieder()->detach($mitglied);

        return response([
            'success' => !$ausrueckung->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied ' . $mitglied->vorname . ' ' . $mitglied->zuname . ' entfernt!'
        ], 200);
    }

    public function attachMitgliedToGruppe(Request $request)
    {
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'gruppe_id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($fields['mitglied_id']);
        $gruppe = Gruppe::findOrFail($fields['gruppe_id']);
        $gruppe->mitglieder()->attach($mitglied);

        return response([
            'success' => $gruppe->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied ' . $mitglied->vorname . ' ' . $mitglied->zuname . ' nach ' . $gruppe->name . ' zugewiesen!'
        ], 200);
    }

    public function detachMitgliedFromGruppe(Request $request)
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
            'message' => 'Mitglied ' . $mitglied->vorname . ' ' . $mitglied->zuname . ' von ' . $gruppe->name . ' entfernt!'
        ], 200);
    }
}
