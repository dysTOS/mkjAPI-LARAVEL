<?php

namespace App\Http\Controllers;

use App\classes\ListQueryHandler;
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
        $handler = new ListQueryHandler(Anschrift::class);
        $output = $handler->getListOutput($request);
        return response($output, 200);
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
        if ($this->validateRequest($request)) {
            $anschrift = new Anschrift($request->all());
            $anschrift->save();
            return $anschrift;
        }
    }

    public function update(Request $request, $id)
    {
        if ($this->validateRequest($request)) {
            $anschrift = Anschrift::findOrFail($request['id']);
            $anschrift->update($request->all());
            return $anschrift;
        }
    }

    public function delete(Request $request, $id)
    {
        return Anschrift::destroy($id);
    }

    private function validateRequest(Request $request) : bool{
        if ($request['firma'] || ($request['vorname'] && $request['zuname'])) {
            return true;
        } else {
            return abort(422, 'Fehlende Angaben der Anschrift!');
        }
}
}
