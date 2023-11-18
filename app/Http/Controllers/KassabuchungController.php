<?php

namespace App\Http\Controllers;

use App\Models\Kassabuchung;
use Illuminate\Http\Request;

class KassabuchungController extends Controller implements _CrudControllerInterface
{
    public function getList(Request $request)
    {
        // TODO: Implement getList() method.
    }

    public function getById(Request $request, $id)
    {
        return Kassabuchung::find($id);
    }

    public function create(Request $request)
    {
        $request->validate([
            'typ' => 'required',
            'nummer' => 'required',
            'datum' => 'required',
            'kassabuch_id' => 'required',
            'anschrift_id' => 'required',
            'gesamtpreis' => 'required',
            'positionen' => 'required'
        ]);

        return Kassabuchung::create($request->all());
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update() method.
    }

    public function delete(Request $request, $id)
    {
        // TODO: Implement delete() method.
    }
}
