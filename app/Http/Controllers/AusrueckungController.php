<?php

namespace App\Http\Controllers;

use App\Models\Noten;
use Illuminate\Http\Request;
use App\Models\Ausrueckung;
use Validator;

class AusrueckungController extends Controller
{
    public function getAll()
    {
        return Ausrueckung::all();
    }

    public function getActualYearPublic()
    {
        $actualYear = date('Y') . "-01-01 00:00:00";
        return Ausrueckung::where('oeffentlich', true)
            ->where('von', '>=', $actualYear)->orderBy('von', 'asc')->get();
    }

    public function getFiltered(Request $request)
    {
        $request->validate([
            'vonFilter' => 'required',
            'bisFilter' => 'required',
        ]);

        return Ausrueckung::where('von', '>=', $request->get('vonFilter'))
            ->where('von', '<=', $request->get('bisFilter'))->get();
    }

    public function getNextActualPublic()
    {
        $actualDate = date("Y-m-d H:i:s");
        return Ausrueckung::where('von', '>=', $actualDate)->where('oeffentlich', true)
            ->oldest('von')->first();
    }

    public function getNextActual()
    {
        $actualDate = date("Y-m-d H:i:s");
        return Ausrueckung::where('von', '>=', $actualDate)->oldest('von')->first();
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'kategorie' => 'required',
            'status' => 'required',
            'von' => 'required',
            'bis' => 'required'
        ]);

        return Ausrueckung::create($request->all());
    }

    public function duplicate(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $duplicate = Ausrueckung::find($request->get('id'));
        $duplicate->id = null;
        return Ausrueckung::create($duplicate);
    }

    public function getSingle($id)
    {
        return Ausrueckung::find($id);
    }

    public function update(Request $request, $id)
    {
        $ausrueckung = Ausrueckung::find($id);
        $ausrueckung->update($request->all());
        return $ausrueckung;
    }

    public function destroy($id)
    {
        Ausrueckung::destroy($id);
    }


    public function search($name)
    {
        return Ausrueckung::where('name', 'like', '%'.$name.'%')->get();
    }
}
