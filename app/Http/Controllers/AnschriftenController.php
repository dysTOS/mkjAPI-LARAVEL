<?php

namespace App\Http\Controllers;

use App\Models\Anschrift;
use CrudController;
use Illuminate\Http\Request;

class AnschriftenController extends Controller implements _CrudControllerInterface
{


    public function getList(Request $request)
    {
        return Anschrift::all();
    }

    public function getById(Request $request, $id)
    {
        return Anschrift::find($id);
    }

    public function create(Request $request)
    {
        $request->validate([
            'zuname' => 'exclude_if:firma|required|string',
            'vorname' => 'exclude_if:firma|required|string'
        ]);

        return Anschrift::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'zuname' => 'exclude_if:firma|required|string',
            'vorname' => 'exclude_if:firma|required|string'
        ]);

        $anschrift = Anschrift::find($id);
        $anschrift->update($request->all());
        return $anschrift;
    }

    public function delete(Request $request, $id)
    {
        return Anschrift::destroy($id);
    }
}
