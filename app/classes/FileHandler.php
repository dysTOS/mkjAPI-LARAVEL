<?php

namespace App\classes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileHandler
{
    private string $pathPrefix = "";

    public function __construct($pathPrefix)
    {
        if($pathPrefix != ""){
            $this->pathPrefix = $pathPrefix;
        }
    }

    public function getFolders($path = "") {
        return Storage::directories($this->pathPrefix . $path);
    }

    public function getFiles($path = "") {
        return Storage::files($this->pathPrefix . $path);
    }

    public function download($filePath) {
        Storage::exists($this->pathPrefix . $filePath);
        $path = $this->pathPrefix . $filePath;
        return Storage::download($path);
    }

    public function upload(Request $request) {
        return $request->file('file')->storeAs("/ff", "sf");
    }
}
