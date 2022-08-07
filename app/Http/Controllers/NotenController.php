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

        $this->middleware('permission:noten_read', ['only' => ['getAll','search', 'getNotenOfAusrueckung']]);
        $this->middleware('permission:noten_save', ['only' => ['create', 'update']]);
        $this->middleware('permission:noten_delete', ['only' => ['destroy']]);
        $this->middleware('permission:noten_assign', ['only' => ['attachNoten', 'detachNoten']]);
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
}
