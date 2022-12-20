<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ausrueckungen_read', ['only' => ['getAll', 'save']]);
    }

    public static function getAll()
    {
        return Report::all();
    }

    public static function save(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        return Report::create($request->all());
    }
}
