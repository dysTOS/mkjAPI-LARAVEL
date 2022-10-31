<?php

namespace App\Http\ViewControllers;
use App\Http\Controllers\Controller;


class HomeViewController extends Controller
{
    public function getHomeData()
    {
        $nextAusrueckung = app('App\Http\Controllers\AusrueckungController')->getNextActualPublic();
        return view('pages.home')->with(array(
            'nextAusrueckung' => $nextAusrueckung
        ));
    }

    public function getTermineData()
    {
        $ausrueckungen = app('App\Http\Controllers\AusrueckungController')->getActualYearPublic();
        return view('pages.termine')->with(array(
            'ausrueckungen' => $ausrueckungen
        ));
    }
}
