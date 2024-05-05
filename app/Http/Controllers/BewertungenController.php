<?php

namespace App\Http\Controllers;

use App\Models\Bewertung;
use Illuminate\Http\Request;
use App\Models\Noten;

class BewertungenController extends Controller
{
    //TODO: make generic for all models
    public function setNotenVote(Request $request)
    {
        $fields = $request->validate([
            'noten_id' => 'required'
        ]);

        $mitglied_id = $request->user()->mitglied_id;
        $noten = Noten::findOrFail($fields['noten_id']);

        $bewertung = $noten->bewertungen()->where('mitglied_id', $mitglied_id)->first();
        if($bewertung) {
            $bewertung->bewertung = $request->bewertung;
            $bewertung->save();
        } else {
            $bewertung = new Bewertung(array(
                'bewertung' => $request->bewertung,
                'mitglied_id' => $mitglied_id
            ));
            $noten->bewertungen()->save($bewertung);
        }

        $noten->bewertung = $noten->bewertungen()->avg('bewertung');
        $noten->save();

        return response(
            [
                'message' => 'Bewertung gespeichert!',
                'bewertung' => $noten->bewertung
            ],
            200
        );
    }

    public function getNotenVote(Request $request)
    {
        $fields = $request->validate([
            'noten_id' => 'required'
        ]);

        $noten = Noten::findOrFail($fields['noten_id']);
        return $noten->bewertungen()->where('mitglied_id', $request->user()->mitglied_id)->first();
    }
}
