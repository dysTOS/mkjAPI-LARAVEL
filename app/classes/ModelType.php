<?php

namespace App\classes;

use App\Models\Kommentar;
use App\Models\Mitglieder;
use App\Models\Noten;

class ModelType
{
    public const KOMMENTAR = 'kommentar';
    public const MITGLIED = 'mitglied';
    public const NOTEN = 'noten';


    public static function getModel($modelType, $id)
    {
        if($modelType === ModelType::NOTEN)
        {
            return Noten::findOrFail($id);
        }
        if($modelType === ModelType::MITGLIED)
        {
            return Mitglieder::findOrFail($id);
        }
        if($modelType === ModelType::KOMMENTAR)
        {
            return Kommentar::findOrFail($id);
        }
        return abort(404, 'Model-Typ nicht gefunden!');
    }
}
