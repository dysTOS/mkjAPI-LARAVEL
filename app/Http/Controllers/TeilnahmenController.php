<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitglieder;
use App\Models\Ausrueckung;
use App\Models\Gruppe;
use Illuminate\Support\Facades\DB;

class TeilnahmenController extends Controller
{
    public function getTeilnahmeStatus(Request $request)
    {
        $fields = $request->validate([
            'termin_id' => 'required',
        ]);

        $user = $request->user();
        $mitglied = Mitglieder::findOrFail($user->mitglied_id);
        $ausrueckung = Ausrueckung::findOrFail($fields['termin_id']);

        return DB::table('teilnahmen')
            ->select('status')
            ->where('termin_id', '=', $ausrueckung->id)
            ->where('mitglied_id', '=', $mitglied->id)
            ->first();
    }

    public function getTeilnahmenForTermin(Request $request)
    {
        $fields = $request->validate([
            'termin_id' => 'required',
        ]);

        return Gruppe::where('register', true)->with([
            'mitglieder.teilnahmen' => function ($query) use ($fields) {
                $query->where('termin_id', $fields['termin_id']);
            }
        ])->orderBy('name')->get();


    }

    public function updateTeilnahme(Request $request){
        $fields = $request->validate([
            'termin_id' => 'required',
            'status' => 'required'
        ]);

        $user = $request->user();
        $mitglied = Mitglieder::findOrFail($user->mitglied_id);
        $ausrueckung = Ausrueckung::findOrFail($fields['termin_id']);
        if($ausrueckung->teilnahmen()->get()->contains($mitglied)){
            $ausrueckung->teilnahmen()->updateExistingPivot($mitglied, ['status' => $fields['status']]);
        }else{
            $ausrueckung->teilnahmen()->attach($mitglied, ['status' => $fields['status']]);
        }

        return response([
            'success' => $ausrueckung->teilnahmen()->get()->contains($mitglied),
            'message' => 'Teilnahme aktualisiert!'
        ], 200);
    }

    public function removeTeilnahme(Request $request){
        $fields = $request->validate([
            'termin_id' => 'required',
        ]);

        $user = $request->user();
        $mitglied = Mitglieder::findOrFail($user->mitglied_id);
        $ausrueckung = Ausrueckung::findOrFail($fields['termin_id']);
        $ausrueckung->teilnahmen()->detach($mitglied);

        return response([
            'success' => !$ausrueckung->teilnahmen()->get()->contains($mitglied),
            'message' => 'Teilnahme entfernt!'
        ], 200);
    }
}
