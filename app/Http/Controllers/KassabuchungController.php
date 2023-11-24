<?php

namespace App\Http\Controllers;

use App\Models\Anschrift;
use App\Models\Kassabuch;
use App\Models\Kassabuchung;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Never_;

class KassabuchungController extends Controller implements _CrudControllerInterface
{
    public function getList(Request $request)
    {
        // TODO: Implement getList() method.
    }

    public function getById(Request $request, $id)
    {
        return Kassabuchung::find($id)->load('anschrift');
    }

    public function create(Request $request)
    {
        $kassabuch = $this->validateKassabuch($request);
        $anschrift = $this->validateBuchung($request);

        $kassastand = $kassabuch->kassastand;
        $kassabuch->kassastand = $kassastand + $request['gesamtpreis'];
        $request['anschrift_id'] = $anschrift->id;
        $buchung = Kassabuchung::create($request->all());
        if ($buchung->id) {
            $kassabuch->save();
        }
        return $buchung;
    }

    public function update(Request $request, $id)
    {
        $kassabuch = $this->validateKassabuch($request);
        $buchung = Kassabuchung::findOrFail($request['id']);
        $anschrift = $this->validateBuchung($request);

        $kassastandAlt = $kassabuch->kassastand;
        $kassastandNeu = $kassastandAlt - $buchung->gesamtpreis + $request['gesamtpreis'];
        $kassabuch->kassastand = $kassastandNeu;
        $request['anschrift_id'] = $anschrift->id;
        $buchung->update($request->all());
        $kassabuch->save();
        return $buchung;
    }

    public function delete(Request $request, $id)
    {
        // TODO: Implement delete() method.
    }


    private function validateBuchung(Request $request) : Anschrift | Never_
    {
        //TODO: validate gesamtpreis

        $anschrift_id = $request['anschrift_id'];
        if ($anschrift_id != null) {
            return Anschrift::findOrFail($anschrift_id);
        } else if ($request['anschrift']['firma'] || ($request['anschrift']['vorname'] && $request['anschrift']['zuname'])) {
            $anschrift =  new Anschrift($request['anschrift']);
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

        if($kassabuch->aktiv == false){
            abort(422, 'Kassabuch ist inaktiv!');
        }

        return $kassabuch;
    }
}
