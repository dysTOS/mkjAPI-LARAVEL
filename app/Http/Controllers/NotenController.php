<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotenController extends Controller
{
    public function getAll(){
        return Noten::all();
    }
}
