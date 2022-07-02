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
        $this->middleware('permission:create notenmappe', ['only' => ['createNotenmappe']]);
        $this->middleware('permission:read notenmappe', ['only' => ['getNotenmappen']]);
        $this->middleware('permission:update notenmappe', ['only' => ['updateNotenmappe']]);
        $this->middleware('permission:delete notenmappe', ['only' => ['destroyNotenmappe']]);
        $this->middleware('permission:assign notenmappe', ['only' => ['notenmappeAttach', 'notenmappeDetach']]);
    }

    public function getNotenmappen()
    {
        return Notenmappe::with('noten')->get();
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
            $request->validate([
                'verzeichnisNr' => 'required'
            ]);
        }

        if($mappe->noten()->get()->contains($noten)){
            abort(403,'Stück bereits zugewiesen!');
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
