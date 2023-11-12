<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\Models\Termin;
use App\Models\Gruppe;
use App\Models\Mitglieder;
use Illuminate\Http\Request;
use Validator;

class TerminController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.PermissionMap::TERMIN_READ, ['only' => ['getAll', 'search', 'getFiltered', 'getNextActual', 'getSingle']]);
        $this->middleware('permission:'.PermissionMap::TERMIN_SAVE, ['only' => ['create', 'update']]);
        $this->middleware('permission:'.PermissionMap::TERMIN_DELETE, ['only' => ['destroy']]);
        $this->middleware('permission:'.PermissionMap::TERMIN_GRUPPENLEITER_SAVE, ['only' => ['saveTerminByGruppenleiter']]);
    }

    public static function saveTerminByGruppenleiter(Request $request)
    {
        $request->validate([
            'gruppe_id' => 'required'
        ]);

        $termin = Termin::find($request->id);
        if($termin && $termin->gruppe_id != $request->gruppe_id){
            abort(403, "Dieser Termin wurde nicht für diese Gruppe erstellt!");
        }

        $mitglied = Mitglieder::where('user_id', $request->user()->id)->first();
        $gruppe = Gruppe::where('gruppenleiter_mitglied_id', '=', $mitglied->id)->where('id', '=', $request->gruppe_id)->first();
        if(!$gruppe){
            abort(403, "Der Termin muss der richtigen Gruppe zugewiesen werden!");
        }

        if($termin){
            return TerminController::update($request, $request->id);
        }else{
            return TerminController::create($request);
        }

    }

    public function getActualYearPublic()
    {
        $actualDate = date("Y-m-d");
        return Termin::where('oeffentlich', true)
            ->where('status', '!=', 'abgesagt')
            ->where('vonDatum', '>=', $actualDate)
            ->orderBy('vonDatum', 'asc')
            ->get();
    }

    public function getNextActualPublic()
    {
        $actualDate = date("Y-m-d");
        return Termin::where('vonDatum', '>=', $actualDate)
            ->where('status', '!=', 'abgesagt')
            ->where('oeffentlich', true)
            ->oldest('vonDatum')->first();
    }


    public function getAll(Request $request)
    {
        $gruppen = Mitglieder::where('user_id', $request->user()->id)->first()->gruppen()->get();
        return Termin::when(
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
        $mitglied = Mitglieder::where('user_id', $request->user()->id)->first();
        $gruppen = $mitglied->gruppen()->get();

        $ausrueckungen = Termin::when(
                $filter, function($query, $filter){
                    foreach($filter as $f){
                        if($f){
                            $query->where($f['filterField'], $f['operator'], $f['value']);
                        }
                    }
                    return $query;
                }
            )->when(
                $gruppen, function($query, $gruppen){
                    $query->where(function($query) use ($gruppen) {
                        foreach($gruppen as $gruppe){
                            if($gruppe){
                                $query->orWhere('gruppe_id', '=', $gruppe['id']);
                            }
                        }
                        return $query->orWhere('gruppe_id', '=', null);
                    });
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
        $skip = $request->get('skip') ?? 0;
        $gruppen = Mitglieder::where('user_id', $request->user()->id)->first()->gruppen()->get();

        return Termin::when(
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
                //if $gruppen is null
                function($query){
                    $actualDate = date("Y-m-d");
                    $query->where('vonDatum', '>=', $actualDate);
                }
            )
            ->oldest('vonDatum')
            ->get()->offsetGet($skip);
    }

    public static function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'kategorie' => 'required',
            'status' => 'required',
            'vonDatum' => 'required',
            'bisDatum' => 'required'
        ]);

        return Termin::create($request->all());
    }

    public function getSingle($id)
    {
        return Termin::find($id);
    }

    public static function update(Request $request, $id)
    {
        $ausrueckung = Termin::find($id);
        $ausrueckung->update($request->all());
        return $ausrueckung;
    }

    public function destroy(Request $request, $id)
    {
        return Termin::destroy($id);
    }
}
