<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\Models\Termin;
use App\Models\Noten;
use App\Models\Notenmappe;
use Illuminate\Http\Request;

class NotenMappenController extends Controller implements _CrudControllerInterface
{
    function __construct()
    {
        $this->middleware('permission:' . PermissionMap::NOTENMAPPE_READ, ['only' => ['getList', 'getById']]);
        $this->middleware('permission:' . PermissionMap::NOTENMAPPE_SAVE, ['only' => ['create', 'update']]);
        $this->middleware('permission:' . PermissionMap::NOTENMAPPE_DELETE, ['only' => ['delete']]);
        $this->middleware('permission:' . PermissionMap::NOTENMAPPE_ASSIGN, ['only' => ['notenmappeAttach', 'notenmappeDetach']]);
    }

    public function getList(Request $request)
    {
        $list = Notenmappe::all()->load('noten');
        return response([
            "totalCount" => $list->count(),
            "values" => $list
        ], 200);
    }

    public function getById(Request $request, $id)
    {
        $mappe = Notenmappe::find($id);
        return $mappe->load('noten');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        return Notenmappe::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $mappe = Notenmappe::find($id);
        $mappe->update($request->all());
        return $mappe;
    }

    public function delete(Request $request, $id)
    {
        Notenmappe::destroy($id);
    }


    public function attach(Request $request)
    {

        $fields = $request->validate([
            'noten_id' => 'required',
            'mappe_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $mappe = Notenmappe::find($fields['mappe_id']);

        if ($mappe->hatVerzeichnis) {
            $fields = $request->validate([
                'verzeichnisNr' => 'required'
            ]);

            if ($mappe->noten()->wherePivot('verzeichnisNr', $fields['verzeichnisNr'])->first()) {
                abort(403, 'Verzeichnis Nr. ist bereits vergeben!');
            }
        }

        if ($mappe->noten()->get()->contains($noten)) {
            abort(403, 'Stück ist bereits zugewiesen!');
        }
        $mappe->noten()->attach($noten, ['verzeichnisNr' => $request['verzeichnisNr']]);

        return response([
            'success' => $mappe->noten()->get()->contains($noten),
            'message' => 'Musikstück ' . $noten->titel . ' zugewiesen!'
        ], 200);
    }

    public function detach(Request $request)
    {
        $fields = $request->validate([
            'noten_id' => 'required',
            'mappe_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $mappe = Notenmappe::find($fields['mappe_id']);
        $mappe->noten()->detach($noten);

        return response([
            'success' => !$mappe->noten()->get()->contains($noten),
            'message' => 'Musikstück ' . $noten->titel . ' entfernt!'
        ], 200);
    }


}
