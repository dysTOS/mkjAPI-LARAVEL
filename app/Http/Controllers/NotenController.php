<?php

namespace App\Http\Controllers;

use App\Models\Ausrueckung;
use App\Models\Konzert;
use App\Models\Noten;
use App\Models\Notenmappe;
use Illuminate\Http\Request;
use function PHPUnit\Framework\throwException;

class NotenController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:create noten', ['only' => ['create']]);
        $this->middleware('permission:read noten', ['only' => ['getAll','search', 'getNotenOfAusrueckung']]);
        $this->middleware('permission:update noten', ['only' => ['update']]);
        $this->middleware('permission:delete noten', ['only' => ['destroy']]);
        $this->middleware('permission:assign noten', ['only' => ['attachNoten', 'detachNoten']]);
    }

    public function attachNoten(Request $request){

        $fields = $request->validate([
            'noten_id' => 'required',
            'ausrueckung_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $ausrueckung = Ausrueckung::find($fields['ausrueckung_id']);

        if($ausrueckung->noten()->get()->contains($noten)){
            abort(403,'Stück bereits zugewiesen!');
        }
        $ausrueckung->noten()->attach($noten);

        return response([
            'success' => $ausrueckung->noten()->get()->contains($noten),
            'message' => 'Musikstück '.$noten->titel.' zugewiesen!'
        ], 200);
    }
    public function detachNoten(Request $request){
        $fields = $request->validate([
            'noten_id' => 'required',
            'ausrueckung_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $ausrueckung = Ausrueckung::find($fields['ausrueckung_id']);
        $ausrueckung->noten()->detach($noten);

        return response([
            'success' => !$ausrueckung->noten()->get()->contains($noten),
            'message' => 'Musikstück '.$noten->titel.' entfernt!'
        ], 200);
    }

    public function getAll(){
        return Noten::all();
    }

    public function getNotenOfAusrueckung($id){
        $ausrueckung = Ausrueckung::find($id);
        $noten = $ausrueckung->noten()->get();
        return $noten;
    }

    public function create(Request $request)
    {
        $request->validate([
            'titel' => 'required',
            'komponist' => 'required'
        ]);

        return Noten::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $noten = Noten::find($id);
        $noten->update($request->all());
        return $noten;
    }

    public function destroy($id)
    {
        Noten::destroy($id);
    }

    public function search($name)
    {
        return Noten::where('titel', 'like', '%'.$name.'%')->get();
    }

    public function  getNotenmappen()
    {
        return Notenmappe::all();
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
        Noten::destroy($id);
    }

    public function notenmappeAttach(Request $request){

        $fields = $request->validate([
            'noten_id' => 'required',
            'mappe_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $mappe = Ausrueckung::find($fields['ausrueckung_id']);

        if($mappe->noten()->get()->contains($noten)){
            abort(403,'Stück bereits zugewiesen!');
        }
        $mappe->noten()->attach($noten);

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
        $mappe = Ausrueckung::find($fields['ausrueckung_id']);
        $mappe->noten()->detach($noten);

        return response([
            'success' => !$mappe->noten()->get()->contains($noten),
            'message' => 'Musikstück '.$noten->titel.' entfernt!'
        ], 200);
    }

    public function  getKonzerte()
    {
        return Konzert::all();
    }

    public function createKonzert(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'datum' => 'required',
            'ort' => 'required'
        ]);

        return Konzert::create($request->all());
    }

    public function updateKonzert(Request $request, $id)
    {
        $konzert = Konzert::find($id);
        $konzert->update($request->all());
        return $konzert;
    }

    public function destroyKonzert($id)
    {
        Konzert::destroy($id);
    }

    public function konzertAttach(Request $request){

        $fields = $request->validate([
            'noten_id' => 'required',
            'konzert_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $konzert = Ausrueckung::find($fields['ausrueckung_id']);

        if($konzert->noten()->get()->contains($noten)){
            abort(403,'Stück bereits zugewiesen!');
        }
        $konzert->noten()->attach($noten);

        return response([
            'success' => $konzert->noten()->get()->contains($noten),
            'message' => 'Musikstück '.$noten->titel.' zugewiesen!'
        ], 200);
    }
    public function konzertDetach(Request $request){
        $fields = $request->validate([
            'noten_id' => 'required',
            'konzert_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $konzert = Ausrueckung::find($fields['ausrueckung_id']);
        $konzert->noten()->detach($noten);

        return response([
            'success' => !$konzert->noten()->get()->contains($noten),
            'message' => 'Musikstück '.$noten->titel.' entfernt!'
        ], 200);
    }
}
