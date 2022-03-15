<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitglieder;
use Validator;

class MitgliederController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        return Mitglieder::all();
    }

    public function getAllActive()
    {
        return Mitglieder::where('aktiv', true)->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'vorname' => 'required',
            'zuname' => 'required',
            'email' => 'required',
        ]);

        return Mitglieder::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSingle($id)
    {
        return Mitglieder::find($id);
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
        $mitglied = Mitglieder::find($id);
        $mitglied->update($request->all());
        return $mitglied;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Mitglieder::destroy($id);
    }


    public function search($name)
    {
        return Mitglieder::where('zuname', 'like', '%'.$name.'%')
            ->orWhere('vorname', 'like', '%'.$name.'%')->get();
    }
}
