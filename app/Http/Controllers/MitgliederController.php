<?php

namespace App\Http\Controllers;

use App\Models\Ausrueckung;
use App\Models\Noten;
use Illuminate\Http\Request;
use App\Models\Mitglieder;
use Validator;

class MitgliederController extends Controller
{
    public function attachMitglied(Request $request){
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'ausrueckung_id' => 'required'
        ]);

        $mitglied = Mitglieder::find($fields['mitglied_id']);
        $ausrueckung = Ausrueckung::find($fields['ausrueckung_id']);
        $ausrueckung->mitglieder()->attach($mitglied);

        return response([
            'success' => $ausrueckung->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied '.$mitglied->vorname.' '.$mitglied->zuname.' zugewiesen!'
        ], 200);
    }
    public function detachMitglied(Request $request){
        $fields = $request->validate([
            'mitglied_id' => 'required',
            'ausrueckung_id' => 'required'
        ]);

        $mitglied = Mitglieder::find($fields['mitglied_id']);
        $ausrueckung = Ausrueckung::find($fields['ausrueckung_id']);
        $ausrueckung->mitglieder()->detach($mitglied);

        return response([
            'success' => !$ausrueckung->mitglieder()->get()->contains($mitglied),
            'message' => 'Mitglied '.$mitglied->vorname.' '.$mitglied->zuname.' entfernt!'
        ], 200);
    }
    public function getAll()
    {
        return Mitglieder::all();
    }

    public function getAllActive()
    {
        return Mitglieder::where('aktiv', true)->get();
    }

    public function getMitgliederOfAusrueckung($id){
        $ausrueckung = Ausrueckung::find($id);
        $mitglieder = $ausrueckung->mitglieder()->get();
        return $mitglieder;
    }

    public function create(Request $request)
    {
        $request->validate([
            'vorname' => 'required',
            'zuname' => 'required',
            'email' => 'required',
        ]);

        return Mitglieder::create($request->all());
    }

    public function getSingle($id)
    {
        return Mitglieder::find($id);
    }

    public function update(Request $request, $id)
    {
        $mitglied = Mitglieder::find($id);
        $mitglied->update($request->all());
        return $mitglied;
    }

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
