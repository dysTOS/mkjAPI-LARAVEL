<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\DAO\ListQueryDAO;
use App\Models\Termin;
use App\Models\Gruppe;
use App\Models\Mitglieder;
use Illuminate\Http\Request;
use Validator;

class TerminController extends Controller implements _CrudControllerInterface
{
    function __construct()
    {
        $this->middleware('permission:'.PermissionMap::TERMIN_READ, ['only' => ['getAll', 'search', 'getFiltered', 'getNextActual', 'getSingle']]);
        $this->middleware('permission:'.PermissionMap::TERMIN_SAVE, ['only' => ['create', 'update']]);
        $this->middleware('permission:'.PermissionMap::TERMIN_DELETE, ['only' => ['destroy']]);
        $this->middleware('permission:'.PermissionMap::TERMIN_GRUPPENLEITER_SAVE, ['only' => ['saveTerminByGruppenleiter']]);
    }

    public function saveTerminByGruppenleiter(Request $request)
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
            return $this->update($request, $request->id);
        }else{
            return $this->create($request);
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

    public function create(Request $request)
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


    public function update(Request $request, $id)
    {
        $ausrueckung = Termin::findOrFail($id);
        $ausrueckung->update($request->all());
        return $ausrueckung;
    }


    public function getList(Request $request)
    {
        $handler = new ListQueryDAO(Termin::class, array('load' => 'gruppe', 'preFilterGruppen' => true));
        $output = $handler->getListOutput($request);
        return response($output, 200);
    }

    public function getById(Request $request, $id)
    {
        return Termin::findOrFail($id);
    }

    public function delete(Request $request, $id)
    {
        return Termin::destroy($id);
    }
}
