<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\DAO\ListQueryDAO;
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
        $handler = new ListQueryDAO(Kassabuch::class, array('load' => 'gruppe'));
        $output = $handler->getListOutput($request);
        return response($output, 200);
    }

    public function getById(Request $request, $id)
    {
        return Kassabuch::findOrFail($id)->load('gruppe');
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
