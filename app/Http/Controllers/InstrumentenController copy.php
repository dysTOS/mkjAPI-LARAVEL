<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\Mitglieder;
use Illuminate\Http\Request;

class InstrumentenController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:instrumente_read', ['only' => ['getAll','getInstrumenteOfMitglied']]);
        $this->middleware('permission:instrumente_save', ['only' => ['save']]);
        $this->middleware('permission:instrumente_delete', ['only' => ['destroy']]);
        $this->middleware('permission:instrumente_assign', ['only' => ['attachInstrument', 'detachInstrument']]);
    }

    public function attachInstrument(Request $request)
    {
        $request->validate([
            'mitglied_id' => 'required',
            'instrument_id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($request->mitglied_id);
        $instrument = Instrument::findOrFail($request->instrument_id);
        $mitglied->instrumente()->attach($instrument);
        return $mitglied->instrumente()->get();
    }

    public function detachInstrument(Request $request)
    {
        $request->validate([
            'mitglied_id' => 'required',
            'instrument_id' => 'required'
        ]);

        $mitglied = Mitglieder::findOrFail($request->mitglied_id);
        $instrument = Instrument::findOrFail($request->instrument_id);
        $mitglied->instrumente()->detach($instrument);
        return $mitglied->instrumente()->get();
    }


    public function getAll(){
        return Instrument::all();
    }

    public static function getInstrumentById(Request $request, $id)
    {
        return Instrument::find($id);
    }

    public function getInstrumenteOfMitglied($id){
        $mitglied = Mitglieder::find($id);
        return $mitglied->instrumente()->get();
    }

    public function save(Request $request)
    {
        $request->validate([
            'marke' => 'required',
            'bezeichnung' => 'required'
        ]);

        if($request->id){
            $instrument = Instrument::find($request->id);
            $instrument->update($request->all());
            return $instrument;
        }else{
            return Instrument::create($request->all());
        }
    }


    public function destroy($id)
    {
        Instrument::destroy($id);
    }
}
