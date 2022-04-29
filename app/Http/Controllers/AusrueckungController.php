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
        $actualYear = date('Y') . "-01-01";
        return Ausrueckung::where('oeffentlich', true)
            ->where('vonDatum', '>=', $actualYear)->orderBy('vonDatum', 'asc')->get();
    }

    public function getFiltered(Request $request)
    {
        $request->validate([
            'vonFilter' => 'required',
            'bisFilter' => 'required',
        ]);

        return Ausrueckung::where('vonDatum', '>=', $request->get('vonFilter'))
            ->where('vonDatum', '<=', $request->get('bisFilter'))->get();
    }

    public function getNextActualPublic()
    {
        $actualDate = date("Y-m-d");
        return Ausrueckung::where('vonDatum', '>=', $actualDate)->where('oeffentlich', true)
            ->oldest('vonDatum')->first();
    }

    public function getNextActual()
    {
        $actualDate = date("Y-m-d");
        return Ausrueckung::where('vonDatum', '>=', $actualDate)->oldest('vonDatum')->first();
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'kategorie' => 'required',
            'status' => 'required',
            'vonDatum' => 'required',
            'bisDatum' => 'required'
        ]);

        return Ausrueckung::create($request->all());
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
