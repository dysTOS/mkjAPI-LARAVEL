<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\Models\Kassabuch;
use Illuminate\Http\Request;

class KassabuchController extends Controller implements _CrudControllerInterface
{
    function __construct()
    {
        $this->middleware('permission:' . PermissionMap::KASSABUCH_READ, ['only' =>
            ['getList', 'getById']]);
        $this->middleware('permission:' . PermissionMap::KASSABUCH_SAVE, ['only' => ['create', 'update']]);
        $this->middleware('permission:' . PermissionMap::KASSABUCH_DELETE, ['only' => ['delete']]);
    }

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
        return Kassabuch::find($id)->load('gruppe')->load('kassabuchungen');
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

        $kassabuch = Kassabuch::findOrFail($id);
        $kassabuch->update($request->all());
        return $kassabuch;
    }

    public function delete(Request $request, $id)
    {
        return Kassabuch::destroy($id);
    }
}
