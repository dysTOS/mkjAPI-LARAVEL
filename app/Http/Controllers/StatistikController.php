<?php

namespace App\Http\Controllers;

use App\Models\Ausrueckung;
use App\Models\Gruppe;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    public static function getTermine(Request $request)
    {
        return DB::table('ausrueckungen')
                ->groupBy('kategorie')
                 ->select('kategorie as label', DB::raw('count(*) as count'))
                 ->get();
    }
}
