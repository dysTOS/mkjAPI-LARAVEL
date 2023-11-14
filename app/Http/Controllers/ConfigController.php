<?php

namespace App\Http\Controllers;

use App\Configurations\defaults\TerminKategorien;
use App\Configurations\defaults\UiNamingConfig;
use App\Configurations\PermissionMap;
use Illuminate\Http\Request;


class ConfigController extends Controller
{
    private UiNamingConfig $uiNaming;
    private TerminKategorien $terminKategorien;

    function __construct()
    {
        $this->uiNaming = new UiNamingConfig();
        $this->terminKategorien = new TerminKategorien();

        $this->middleware('permission:' . PermissionMap::USER_DELETE, ['only' => ['setUiConfigs']]);
    }

    public function getUiConfigs()
    {
        return response([
            'uiNaming' => $this->uiNaming->get(),
            'terminConfig' => [
                'terminKategorien' => $this->terminKategorien->get()
            ]
        ], 200);
    }

    public function setUiConfigs(Request $request)
    {
        $request->validate([
            'uiNaming' => 'required',
            'terminConfig' => 'required'
        ]);

        $this->uiNaming->permit($request->uiNaming);
        $this->terminKategorien->permit($request->terminConfig['terminKategorien']);

        return $request;
    }
}
