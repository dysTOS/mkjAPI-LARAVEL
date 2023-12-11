<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\Models\Instrument;
use App\Models\Mitglieder;
use Illuminate\Http\Request;

class InstrumentenController extends Controller implements _CrudControllerInterface
{
    function __construct()
    {

        $this->middleware('permission:' . PermissionMap::INSTRUMENTE_READ, ['only' => ['getList', 'getById', 'getInstrumenteOfMitglied']]);
        $this->middleware('permission:' . PermissionMap::INSTRUMENTE_SAVE, ['only' => ['create', 'update']]);
        $this->middleware('permission:' . PermissionMap::INSTRUMENTE_DELETE, ['only' => ['delete']]);
    }

    public function getList(Request $request)
    {
        $list = Instrument::all()->load('mitglied')->load('gruppe');
        return response([
            "totalCount" => $list->count(),
            "values" => $list
        ], 200);
    }

    public function getById(Request $request, $id)
    {
        return Instrument::find($id)->load('mitglied')->load('gruppe');
    }

    public function getInstrumenteOfMitglied($id)
    {
        $mitglied = Mitglieder::find($id);
        return $mitglied->instrumente()->get();
    }

    public function create(Request $request)
    {
        $request->validate([
            'marke' => 'required',
            'bezeichnung' => 'required'
        ]);
        return Instrument::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'marke' => 'required',
            'bezeichnung' => 'required'
        ]);
        $instrument = Instrument::find($request->id);
        $instrument->update($request->all());
        return $instrument;
    }

    public function delete(Request $request, $id)
    {
        Instrument::destroy($id);
    }
}
