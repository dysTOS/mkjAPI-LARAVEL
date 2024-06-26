<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\DAO\ListQueryDAO;
use App\Models\Noten;
use App\Models\Notenmappe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotenMappenController extends Controller implements _CrudControllerInterface
{
    function __construct()
    {
        $this->middleware('permission:' . PermissionMap::NOTENMAPPE_READ, ['only' => ['getList', 'getById', 'getNotenOfMappe']]);
        $this->middleware('permission:' . PermissionMap::NOTENMAPPE_SAVE, ['only' => ['create', 'update']]);
        $this->middleware('permission:' . PermissionMap::NOTENMAPPE_DELETE, ['only' => ['delete']]);
        $this->middleware('permission:' . PermissionMap::NOTENMAPPE_ASSIGN, ['only' => ['syncNoten', 'notenmappeAttach', 'notenmappeDetach']]);
    }

    public function getList(Request $request)
    {
        $handler = new ListQueryDAO(Notenmappe::class, array('load' => 'noten'));
        $output = $handler->getListOutput($request);
        return response($output, 200);
    }

    public function getById(Request $request, $id)
    {
        $mappe = Notenmappe::find($id);
        return $mappe;
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

    public function syncNoten(Request $request)
    {
        $fields = $request->validate([
            'collectionId' => 'required',
            'values' => 'required'
        ]);

        $mappe = Notenmappe::findOrFail($fields['collectionId']);
        $syncArray = array();
        foreach ($fields['values'] as $note) {
            $syncArray[$note['id']] = ['orderIndex' => $note['pivot']['orderIndex'],
                'verzeichnisNr' => $note['pivot']['verzeichnisNr']];
        }
        $mappe->noten()->sync($syncArray);

        return (response([
            'success' => true,
            'message' => $syncArray
        ], 200));
    }


    public function attach(Request $request)
    {

        $fields = $request->validate([
            'noten_id' => 'required',
            'mappe_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $mappe = Notenmappe::find($fields['mappe_id']);

        if ($mappe->noten()->get()->contains($noten)) {
            abort(403, 'Stück ist bereits zugewiesen!');
        }
        $mappe->noten()->attach($noten);

        $this->calculateDauer($mappe, $mappe->noten()->get());

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

        $this->calculateDauer($mappe, $mappe->noten()->get());

        return response([
            'success' => !$mappe->noten()->get()->contains($noten),
            'message' => 'Musikstück ' . $noten->titel . ' entfernt!'
        ], 200);
    }

    public function getNotenOfMappe(Request $request, $id)
    {
        if ($id == null) {
            abort(500, "Keine Mappen ID angegeben!");
        }

        $mappe = Notenmappe::findOrFail($request['id']);

        return $mappe->noten()->get();
    }

    private function calculateDauer($mappe, $noten)
    {
        // Convert each time duration to seconds and sum them up
        $totalSeconds = $noten->map(function ($note) {
            if($note['dauer'] == null) return 0;
            $parts = explode(':', $note['dauer']);
            $hours = (int)$parts[0];
            $minutes = (int)$parts[1];
            $seconds = (int)$parts[2];
            return $hours * 3600 + $minutes * 60 + $seconds;
        })->sum();

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        $dauer= sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $mappe->dauer = $dauer;
        $mappe->save();
    }

}
