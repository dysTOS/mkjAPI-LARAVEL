<?php

namespace App\Http\Controllers;

use App\classes\FileHandler;
use App\Notifications\TestSocket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;


class XXXTestController extends Controller
{
    public function testGet(Request $request)
    {
        $controller = new FileHandler("Documents/");
        return $controller->getFolders();
        return $controller->download('2023/IMG-20230715-WA0017.jpg');
    }

    public function testPost(Request $request)
    {
        //test your shit here Rolando
        $controller = new FileHandler("Fotoarchiv/");
        return $controller->upload($request);
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
