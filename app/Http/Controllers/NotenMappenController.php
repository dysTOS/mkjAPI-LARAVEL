<?php

namespace App\Http\Controllers;

use App\Models\Ausrueckung;
use App\Models\Noten;
use App\Models\Notenmappe;
use Illuminate\Http\Request;

class NotenMappenController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:notenmappe_read', ['only' => ['getNotenmappen', 'getNotenmappe']]);
        $this->middleware('permission:notenmappe_save', ['only' => ['createNotenmappe', 'updateNotenmappe']]);
        $this->middleware('permission:notenmappe_delete', ['only' => ['destroyNotenmappe']]);
        $this->middleware('permission:notenmappe_assign', ['only' => ['notenmappeAttach', 'notenmappeDetach']]);
    }

    public function getNotenmappen()
    {
        return Notenmappe::all();
    }

    public function getNotenmappe(Request $request)
    {
        $fields = $request->validate([
            'id' => 'required'
        ]);
        $mappe = Notenmappe::find($fields['id']);
        return $mappe->load('noten');
    }

    public function createNotenmappe(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        return Notenmappe::create($request->all());
    }

    public function updateNotenmappe(Request $request, $id)
    {
        $mappe = Notenmappe::find($id);
        $mappe->update($request->all());
        return $mappe;
    }

    public function destroyNotenmappe($id)
    {
        Notenmappe::destroy($id);
    }

    public function notenmappeAttach(Request $request){

        $fields = $request->validate([
            'noten_id' => 'required',
            'mappe_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $mappe = Notenmappe::find($fields['mappe_id']);

        if($mappe->hatVerzeichnis){
            $fields = $request->validate([
                'verzeichnisNr' => 'required'
            ]);

            if($mappe->noten()->wherePivot('verzeichnisNr', $fields['verzeichnisNr'])->first())
            {
                abort(403, 'Verzeichnis Nr. ist bereits vergeben!');
            }
        }

        if($mappe->noten()->get()->contains($noten)){
            abort(403,'Stück ist bereits zugewiesen!');
        }
        $mappe->noten()->attach($noten, ['verzeichnisNr' => $request['verzeichnisNr']]);

        return response([
            'success' => $mappe->noten()->get()->contains($noten),
            'message' => 'Musikstück '.$noten->titel.' zugewiesen!'
        ], 200);
    }
    public function notenmappeDetach(Request $request){
        $fields = $request->validate([
            'noten_id' => 'required',
            'mappe_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $mappe = Notenmappe::find($fields['mappe_id']);
        $mappe->noten()->detach($noten);

        return response([
            'success' => !$mappe->noten()->get()->contains($noten),
            'message' => 'Musikstück '.$noten->titel.' entfernt!'
        ], 200);
    }

}
