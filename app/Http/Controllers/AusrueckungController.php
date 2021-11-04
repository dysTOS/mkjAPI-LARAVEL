<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ausrueckung;

class AusrueckungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Ausrueckung::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'von' => 'required',
            'bis' => 'required'
        ]);

        return Ausrueckung::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Ausrueckung::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ausrueckung = Ausrueckung::find($id);
        $ausrueckung->update($request->all());
        return $ausrueckung;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Ausrueckung::destroy($id);
    }


    public function search($name)
    {
        return Ausrueckung::where('name', 'like', '%'.$name.'%')->get();
    }
}
