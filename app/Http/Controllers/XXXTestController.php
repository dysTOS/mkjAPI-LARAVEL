<?php

namespace App\Http\Controllers;

use App\Models\Ausrueckung;
use App\Models\Mitglieder;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\GruppenController;

class XXXTestController extends Controller
{
    public function testGet(Request $request)
    {
        //test your shit here Rolando
        $user = User::where('id', 'cb6dc9a4-97d4-4885-a5b8-c544d41e22e2')->first();
        $gruppen = Mitglieder::where('user_id', $user->id)->first()->gruppen()->get();
        $events = Ausrueckung::when(
            $gruppen, function ($query, $gruppen) {
            foreach ($gruppen as $gruppe) {
                if ($gruppe) {
                    $query->orWhere('gruppe_id', '=', $gruppe['id']);
                }
            }
            return $query->orWhere('gruppe_id', '=', null);
        }
        )->where('vonDatum', '>=', '2023-01-01')->orderBy('vonDatum', 'asc')->get();
        return response(["events" => $events], 200);
    }

    public function testPost(Request $request)
    {
        //test your shit here Rolando
        return AusrueckungController::saveTerminByGruppenleiter($request);
    }

    public function testPut(Request $request)
    {
        //test your shit here Rolando
        return "testPut";
    }

    public function testDelete(Request $request, $id)
    {
        //test your shit here Rolando
        return GruppenController::deleteGruppe($request, $id);
    }


    private function getNotenFiltered(Request $request)
    {
        $request->validate([
            'vonFilter' => 'required',
            'bisFilter' => 'required',
        ]);

        return Ausrueckung::where('vonDatum', '>=', $request->get('vonFilter'))
            ->where('vonDatum', '<=', $request->get('bisFilter'))->get();
    }

}
