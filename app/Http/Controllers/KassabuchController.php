<?php

namespace App\Http\Controllers;

use App\Models\Kassabuch;
use Illuminate\Http\Request;

class KassabuchController extends Controller implements _CrudControllerInterface
{

    public function getList(Request $request)
    {
        $list = Kassabuch::all()->load('gruppe');
        return response([
            "totalCount" => $list->count(),
            "values" => $list
        ], 200);
    }

    public function getById(Request $request, $id)
    {
        return Kassabuch::find($id)->load('grupppe')->load('kassabuchungen');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:kassabuch,name',
        ]);

        return Kassabuch::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $kassabuch = Kassabuch::find($id);
        $kassabuch->update($request->all());
        return $kassabuch;
    }

    public function delete(Request $request, $id)
    {
        return Kassabuch::destroy($id);
    }
}
