<?php

namespace App\Http\Controllers;

use App\classes\ModelType;
use App\Configurations\PermissionMap;
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

        $model = ModelType::getModel($request->commentable_type, $request->commentable_id);
        $mitglied = Mitglieder::findOrFail($request->user()->mitglied_id);

        $kommentar = new Kommentar();
        $kommentar->text = $request->text;
        $kommentar->mitglied_id = $mitglied->id;
        $kommentar->mitglied_name = $mitglied->vorname . ' ' . $mitglied->zuname;
        $kommentar->number_child_comments = 0;

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

        if($user->mitglied_id === $kommentar->mitglied_id && $kommentar->mitglied_name != '')
        {
            $kommentar->text = 'Dieser Kommentar wurde vom Kommentator selbst gelöscht!';
            $kommentar->mitglied_name = '';
            $kommentar->save();
            return $kommentar;
        }

        if (!$user->permissions(PermissionMap::USER_DELETE))
        {
            abort(403, 'Keine Berechtigung!');
        }

        if($kommentar->parent_comment_id)
        {
            $parentComment = Kommentar::findOrFail($kommentar->parent_comment_id);
            $parentComment->number_child_comments--;
            $parentComment->save();
        }

        return Kommentar::destroy($id);
    }
}
