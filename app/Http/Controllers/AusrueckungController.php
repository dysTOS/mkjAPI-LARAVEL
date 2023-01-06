<?php

namespace App\Http\Controllers;

use App\Models\Ausrueckung;
use App\Models\Mitglieder;
use Illuminate\Http\Request;
use Validator;

class AusrueckungController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ausrueckungen_read', ['only' => ['getAll', 'search', 'getFiltered', 'getNextActual', 'getSingle']]);
        $this->middleware('permission:ausrueckungen_save', ['only' => ['create', 'update']]);
        $this->middleware('permission:ausrueckungen_delete', ['only' => ['destroy']]);
    }

    public function getActualYearPublic()
    {
        $actualYear = date('Y') . "-01-01";
        return Ausrueckung::where('oeffentlich', true)
            ->where('status', '!=', 'abgesagt')
            ->where('gruppe_id', '=', null)
            ->where('vonDatum', '>=', $actualYear)
            ->orderBy('vonDatum', 'asc')
            ->get();
    }

    public function getNextActualPublic()
    {
        $actualDate = date("Y-m-d");
        return Ausrueckung::where('vonDatum', '>=', $actualDate)
            ->where('status', '!=', 'abgesagt')
            ->where('oeffentlich', true)
            ->where('gruppe_id', '=', null)
            ->oldest('vonDatum')->first();
    }


    public function getAll(Request $request)
    {
        $gruppen = Mitglieder::where('user_id', $request->user()->id)->first()->gruppen()->get();
        return Ausrueckung::when(
            $gruppen, function($query, $gruppen){
            foreach($gruppen as $gruppe){
                if($gruppe){
                    $query->orWhere('gruppe_id', '=', $gruppe['id']);
                }
            }
            return $query->orWhere('gruppe_id', '=', null);
        }
        )->get();
    }

    public function getFiltered(Request $request)
    {
        $filter = $request->get('filterAnd');
        $gruppen = Mitglieder::where('user_id', $request->user()->id)->first()->gruppen()->get();

        $ausrueckungen = Ausrueckung::when(
            $gruppen, function($query, $gruppen){
            foreach($gruppen as $gruppe){
                if($gruppe){
                    $query->orWhere('gruppe_id', '=', $gruppe['id']);
                }
            }
            return $query->orWhere('gruppe_id', '=', null);
            }
            )
            ->when(
                $filter, function($query, $filter){
                    foreach($filter as $f){
                        if($f){
                            $query->where($f['filterField'], $f['operator'], $f['value']);
                        }
                    }
                    return $query;
                }
            )
            ->skip($request->get('skip') ?? 0)
            ->take($request->get('take') ?? PHP_INT_MAX)
            ->get();

        return response([
            'values' => $ausrueckungen->load('gruppe'),
            'totalCount' => $ausrueckungen->count()],
            200);
    }

    public function getNextActual(Request $request)
    {
        $gruppen = Mitglieder::where('user_id', $request->user()->id)->first()->gruppen()->get();
        return Ausrueckung::when(
                $gruppen, function($query, $gruppen){
                    $actualDate = date("Y-m-d");
                    $query->where('vonDatum', '>=', $actualDate);
                    $query->where(function($query) use ($gruppen){
                        foreach($gruppen as $gruppe){
                            if($gruppe){
                                $query->orWhere('gruppe_id', '=', $gruppe['id']);
                            }
                        }
                        return $query->orWhere('gruppe_id', '=', null);
                    });
                },
                function($query){
                    $actualDate = date("Y-m-d");
                    $query->where('vonDatum', '>=', $actualDate);
                }
            )
            ->oldest('vonDatum')
            ->first();
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

    /**
     * @deprecated check if used || implement gruppen pre-filter
     * @param $name
     * @return mixed*/
    public function search($name)
    {
        return Ausrueckung::where('name', 'like', '%' . $name . '%')->get();
    }
}
