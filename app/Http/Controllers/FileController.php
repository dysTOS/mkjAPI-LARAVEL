<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function storeFile(Request $request)
    {
        $path = $request->file('file')->store(
            '', 'webdav'
        );
        return response(['path' => $path]);
    }

    public function getFiles(){
        $headers = ['Content-Type: image/png'];

        return Storage::disk('webdav')->download('41Gp0px5MRR71mS4DApr2aPgfOM1YXNqymF08jvV.png', 'test.png', $headers);

    }
}
