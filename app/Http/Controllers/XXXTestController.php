<?php

namespace App\Http\Controllers;

use App\Models\Ausrueckung;
use Illuminate\Http\Request;
use App\Http\Controllers\GruppenController;

class XXXTestController extends Controller
{
    public function testGet(Request $request){
        //test your shit here Rolando
        return "testGet";
    }

    public function testPost(Request $request){
        //test your shit here Rolando
        return response([], 200);
    }

    public function testPut(Request $request){
        //test your shit here Rolando
        return "testPut";
    }

    public function testDelete(Request $request, $id){
        //test your shit here Rolando
        return GruppenController::deleteGruppe($request, $id);
    }


    private function getNotenFiltered(Request $request){
        $request->validate([
            'vonFilter' => 'required',
            'bisFilter' => 'required',
        ]);

        return Ausrueckung::where('vonDatum', '>=', $request->get('vonFilter'))
            ->where('vonDatum', '<=', $request->get('bisFilter'))->get();
        }

}
