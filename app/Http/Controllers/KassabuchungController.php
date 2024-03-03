<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\DAO\ListQueryDAO;
use App\Events\KassabuchungUpdated;
use App\Models\Anschrift;
use App\Models\Kassabuch;
use App\Models\Kassabuchung;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Never_;

class KassabuchungController extends Controller implements _CrudControllerInterface
{
    function __construct()
    {
        $this->middleware('permission:' . PermissionMap::KASSABUCHUNG_READ, ['only' =>
            ['getList', 'getById']]);
        $this->middleware('permission:' . PermissionMap::KASSABUCHUNG_SAVE, ['only' => ['create', 'update']]);
        $this->middleware('permission:' . PermissionMap::KASSABUCHUNG_DELETE, ['only' => ['delete']]);

    }

    public function getList(Request $request)
    {
        $handler = new ListQueryDAO(Kassabuchung::class, ['load' => ['anschrift']]);
        $output = $handler->getListOutput($request);
        return response($output, 200);
    }

    public function getById(Request $request, $id)
    {
        return Kassabuchung::find($id)->load('anschrift');
    }

    public function create(Request $request)
    {
        $kassabuch = $this->validateKassabuch($request);
        $anschrift = $this->validateAnschrift($request);
        $this->validatePreise($request);


        $request['anschrift_id'] = $anschrift->id;
        $buchung = Kassabuchung::create($request->all());

        KassabuchungUpdated::dispatch($kassabuch);
        return $buchung;
    }

    public function update(Request $request, $id)
    {
        $kassabuch = $this->validateKassabuch($request);
        $buchung = Kassabuchung::findOrFail($request['id']);
        $anschrift = $this->validateAnschrift($request);
        $this->validatePreise($request);

        $request['anschrift_id'] = $anschrift->id;
        $buchung->update($request->all());

        KassabuchungUpdated::dispatch($kassabuch);
        return $buchung;
    }

    public function delete(Request $request, $id)
    {
        $buchung = Kassabuchung::findOrFail($id);
        $kassabuch = Kassabuch::find($buchung->kassabuch_id);
        $response = Kassabuchung::destroy($id);
        KassabuchungUpdated::dispatch($kassabuch);
        return $response;
    }


    private function validateAnschrift(Request $request): Anschrift|Never_
    {
        $anschrift_id = $request['anschrift_id'];
        if ($anschrift_id != null) {
            return Anschrift::findOrFail($anschrift_id);
        } else if ($request['anschrift']['firma'] || ($request['anschrift']['vorname'] && $request['anschrift']['zuname'])) {
            $anschrift = new Anschrift($request['anschrift']);
            $anschrift->save();
            return $anschrift;
        } else {
            return abort(422, 'Fehlende Angaben der Anschrift!');
        }
    }

    private function validateKassabuch(Request $request): Kassabuch
    {
        $request->validate([
            'typ' => 'required',
            'datum' => 'required',
            'kassabuch_id' => 'required',
            'anschrift_id' => 'required_without:anschrift',
            'anschrift' => 'required_without:anschrift_id',
            'gesamtpreis' => 'required',
        ]);

        $kassabuch = Kassabuch::findOrFail($request['kassabuch_id']);

        if ($kassabuch->aktiv == false) {
            abort(422, 'Kassabuch ist inaktiv!');
        }

        return $kassabuch;
    }

    private function validatePreise(Request $request)
    {
        $gesamtpreis = $request['gesamtpreis'];
        $positionen = $request['positionen'];

        if(count($positionen) == 0){
            return;
        }

        $summe = 0;
        foreach ($positionen as $position) {
            $summe += $position['einzelpreis'];
        }
        if ($gesamtpreis != $summe) {
            $request['gesamtpreis'] = $summe;
        }
    }
}
