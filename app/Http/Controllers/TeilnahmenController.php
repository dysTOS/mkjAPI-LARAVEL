<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitglieder;
use App\Models\Ausrueckung;

class TeilnahmenController extends Controller
{
    public function updateTeilnahme(Request $request){
        $fields = $request->validate([
            'termin_id' => 'required',
            'status' => 'required'
        ]);

        $user = $request->user();
        $mitglied = Mitglieder::findOrFail($user->mitglied_id);
        $ausrueckung = Ausrueckung::findOrFail($fields['ausrueckung_id']);
        $ausrueckung->teilnahmen()->attach($mitglied, ['status' => $fields['status']]);

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
        $ausrueckung = Ausrueckung::findOrFail($fields['ausrueckung_id']);
        $ausrueckung->teilnahmen()->detach($mitglied);

        return response([
            'success' => !$ausrueckung->teilnahmen()->get()->contains($mitglied),
            'message' => 'Teilnahme entfernt!'
        ], 200);
    }
}
