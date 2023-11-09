<?php

namespace App\Http\Controllers;

use App\Constants\PermissionMap;
use App\Constants\UiNamingConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:' . PermissionMap::USER_DELETE, ['only' => ['setUiNamingConfig']]);
    }

    public static function getValueByKey($key)
    {
        $entry = DB::table('config')->where('key', '=', $key)->first();
        if ($entry != null) {
            return $entry->value;
        } else {
            return null;
        }
    }

    public static function storeKeyValue($key, $value)
    {
        DB::table('config')->upsert(['key' => $key, 'value' => $value], ['key']);
    }

    public static function removeKey($key)
    {
        DB::table('config')->where('key', '=', $key)->delete();
    }

    public static function getUiNamingConfig()
    {
        $config = ConfigController::getValueByKey('ui_naming_config');
        if ($config) {
            return $config;
        } else {
            $config = new UiNamingConfig();
            return $config->toJson();
        }
    }

    public static function setUiNamingConfig(Request $request)
    {
        $request->validate([
            'config' => 'required'
        ]);
        ConfigController::storeKeyValue('ui_naming_config', json_encode($request->config));
        return response([], 200);
    }
}
