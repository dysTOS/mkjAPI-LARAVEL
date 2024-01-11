<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\DAO\ListQueryDAO;
use App\Models\Noten;
use App\Models\Termin;
use Illuminate\Http\Request;

class NotenController extends Controller implements _CrudControllerInterface
{
    function __construct()
    {

        $this->middleware('permission:' . PermissionMap::NOTEN_READ, ['only' => ['getList', 'getById', 'search', 'getNotenOfTermin']]);
        $this->middleware('permission:' . PermissionMap::NOTEN_SAVE, ['only' => ['create', 'update']]);
        $this->middleware('permission:' . PermissionMap::NOTEN_DELETE, ['only' => ['delete']]);
        $this->middleware('permission:' . PermissionMap::NOTEN_ASSIGN, ['only' => ['attachNoten', 'detachNoten']]);
    }

    public function getList(Request $request)
    {
        $handler = new ListQueryDAO(Noten::class);
        $output = $handler->getListOutput($request);
        return response($output, 200);
    }

    public function getById(Request $request, $id)
    {
        return Noten::findOrFail($id);
    }

    public function attachNoten(Request $request)
    {

        $fields = $request->validate([
            'noten_id' => 'required',
            'ausrueckung_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $ausrueckung = Termin::find($fields['ausrueckung_id']);

        if ($ausrueckung->noten()->get()->contains($noten)) {
            abort(403, 'Stück bereits zugewiesen!');
        }
        $ausrueckung->noten()->attach($noten);

        return response([
            'success' => $ausrueckung->noten()->get()->contains($noten),
            'message' => 'Musikstück ' . $noten->titel . ' zugewiesen!'
        ], 200);
    }

    public function detachNoten(Request $request)
    {
        $fields = $request->validate([
            'noten_id' => 'required',
            'ausrueckung_id' => 'required'
        ]);

        $noten = Noten::find($fields['noten_id']);
        $ausrueckung = Termin::find($fields['ausrueckung_id']);
        $ausrueckung->noten()->detach($noten);

        return response([
            'success' => !$ausrueckung->noten()->get()->contains($noten),
            'message' => 'Musikstück ' . $noten->titel . ' entfernt!'
        ], 200);
    }

    public function getNotenOfTermin($id)
    {
        $ausrueckung = Termin::find($id);
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

    public function delete(Request $request, $id)
    {
        Noten::destroy($id);
    }

    public function search($name)
    {
        return Noten::where('titel', 'like', '%' . $name . '%')->get();
    }
}
