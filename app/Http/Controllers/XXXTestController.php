<?php

namespace App\Http\Controllers;

use App\Configurations\defaults\TerminKategorien;
use App\Configurations\defaults\UiNamingConfig;
use App\Notifications\TestSocket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;


class XXXTestController extends Controller
{
    public function testGet(Request $request)
    {
        //test your shit here Rolando
//        $class = new TerminKategorien();
//        $naming = new UiNamingConfig();
//        return $naming->toJson();
//        return $class->config;

        $controller = new KassabuchController();
        // return $controller->getList($request);
    }

    public function testPost(Request $request)
    {
        //test your shit here Rolando

        $controller = new BewertungenController();
        // $controller->voteNoten($request);
    }

    public function testPut(Request $request)
    {
        //test your shit here Rolando
        return "testPut";
    }

    public function testDelete(Request $request, $id)
    {
        //test your shit here Rolando
        return "testDelete";
    }

    public function testSocket(Request $request)
    {
        $data = $request['data'];
        Notification::send($request->user(), new TestSocket($data));
        return [];
    }
}
