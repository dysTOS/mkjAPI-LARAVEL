<?php

namespace App\Http\Controllers;

use App\classes\ModelType;
use App\DAO\ListQueryDAO;
use App\Models\Kommentar;
use App\Models\Mitglieder;
use App\Models\Noten;
use Illuminate\Http\Request;

class KommentarController extends Controller implements _CrudControllerInterface
{
    public function getList(Request $request)
    {
        $handler = new ListQueryDAO(Kommentar::class);
        $output = $handler->getListOutput($request);
        return response($output, 200);
    }

    public function getById(Request $request, $id)
    {
        return Kommentar::findOrFail($id);
    }

    public function create(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'commentable_type' => 'required',
            'commentable_id' => 'required',
        ]);

        $model = $this->getModel($request->commentable_type, $request->commentable_id);
        $mitglied = Mitglieder::findOrFail($request->user()->mitglied_id);

        $kommentar = new Kommentar();
        $kommentar->text = $request->text;
        $kommentar->mitglied_id = $mitglied->id;
        $kommentar->mitglied_name = $mitglied->vorname . ' ' . $mitglied->zuname;

        if($request->parent_comment_id)
        {
            $kommentar->parent_comment_id = $request->parent_comment_id;
            $parentComment = Kommentar::findOrFail($request->parent_comment_id);
            $parentComment->number_child_comments++;
            $parentComment->save();
        }

        return $model->comments()->save($kommentar);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'text' => 'required',
        ]);

        $kommentar = Kommentar::findOrFail($id);
        $user = $request->user();

        if ($user->mitglied_id !== $kommentar->mitglied_id &&
         !$user->hasRole('admin'))
        {
            abort(403, 'Keine Berechtigung!');
        }

        $kommentar->text = $request->text;
        return $kommentar->save();
    }


    public function delete(Request $request, $id)
    {
        $kommentar = Kommentar::findOrFail($id);
        $user = $request->user();

        if ($user->mitglied_id !== $kommentar->mitglied_id &&
         !$user->hasRole('admin'))
        {
            abort(403, 'Keine Berechtigung!');
        }

        return Kommentar::destroy($id);
    }

    private function getModel($commentableType, $id)
    {
        if($commentableType === ModelType::NOTEN)
        {
            return Noten::findOrFail($id);
        }
        return abort(404, 'Model-Typ nicht gefunden!');
    }
}
