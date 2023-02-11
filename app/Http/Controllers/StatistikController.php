<?php

namespace App\Http\Controllers;

use App\Models\Ausrueckung;
use App\Models\Gruppe;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    public static function getTermineNachGruppen(Request $request)
    {
        $yearStart = date($request->year) . "-01-01";
        $yearEnd = date($request->year) . "-12-31";

        return Ausrueckung::
                where('vonDatum', '>=', $yearStart)
                ->where('bisDatum', '<', $yearEnd)
                ->get()
                ->countBy('gruppe.name');

    }

    public static function getMitglieder(Request $request)
    {
        return DB::table('mitglieder')
            ->select(DB::raw('YEAR(geburtsdatum) DIV 10 AS label'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('YEAR(geburtsdatum) DIV 10'))
            ->get();
    }

    public static function getMitgliederGeschlecht(Request $request)
    {
        return DB::table('mitglieder')
            ->where('aktiv', true)
            ->groupBy('geschlecht')
            ->select('geschlecht AS label', DB::raw('COUNT(*) as count'))
            ->get();

    }

    public static function getTermine(Request $request)
    {
        $yearStart = date($request->year) . "-01-01";
        $yearEnd = date($request->year) . "-12-31";

        return DB::table('ausrueckungen')
                ->where('vonDatum', '>=', $yearStart)
                ->where('bisDatum', '<', $yearEnd)
                ->groupBy('kategorie')
                 ->select('kategorie as label', DB::raw('count(*) as count'))
                 ->get();
    }

    public static function getNoten(Request $request)
    {
        return DB::table('noten')
                ->groupBy('gattung')
                 ->select('gattung as label', DB::raw('count(*) as count'))
                 ->get();
    }
}
