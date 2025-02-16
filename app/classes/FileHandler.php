<?php

namespace App\Http\Controllers;

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

    public function getDirectories($path = "") {
        return Storage::directories($this->pathPrefix . $path, false);
    }

    public function download($filePath) {
        $path = $this->pathPrefix . $filePath;
        return Storage::download($path);
    }
}
