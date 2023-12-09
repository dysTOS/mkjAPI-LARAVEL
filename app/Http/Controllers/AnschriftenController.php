<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\Models\Anschrift;
use Illuminate\Http\Request;

class AnschriftenController extends Controller implements _CrudControllerInterface
{
    function __construct()
    {
        $this->middleware('permission:' . PermissionMap::ANSCHRIFTEN_READ, ['only' =>
            ['getList', 'getById', 'search']]);
        $this->middleware('permission:' . PermissionMap::ANSCHRIFTEN_SAVE, ['only' => ['create', 'update']]);
        $this->middleware('permission:' . PermissionMap::ANSCHRIFTEN_DELETE, ['only' => ['delete']]);

    }

    public function getList(Request $request)
    {
        return Anschrift::all();
    }

    public function getById(Request $request, $id)
    {
        return Anschrift::find($id);
    }

    public function search(Request $request, $searchString)
    {
        return Anschrift::where('firma', 'like', '%' . $searchString . '%')
            ->orWhere('vorname', 'like', '%' . $searchString . '%')
            ->orWhere('zuname', 'like', '%' . $searchString . '%')
            ->get();
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
