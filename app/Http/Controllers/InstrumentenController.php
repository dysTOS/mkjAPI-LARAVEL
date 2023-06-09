<?php

namespace App\Http\Controllers;

use App\Constants\PermissionMap;
use App\Models\Instrument;
use App\Models\Mitglieder;
use Illuminate\Http\Request;

class InstrumentenController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:'. PermissionMap::INSTRUMENTE_READ, ['only' => ['getAll','getInstrumenteOfMitglied']]);
        $this->middleware('permission:'. PermissionMap::INSTRUMENTE_SAVE, ['only' => ['save']]);
        $this->middleware('permission:'. PermissionMap::INSTRUMENTE_DELETE, ['only' => ['destroy']]);
    }

    public function getAll(){
        return Instrument::all()->load('mitglied')->load('gruppe');
    }

    public static function getInstrumentById(Request $request, $id)
    {
        return Instrument::find($id)->load('mitglied')->load('gruppe');
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
