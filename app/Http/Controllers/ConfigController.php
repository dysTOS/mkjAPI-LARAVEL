<?php

namespace App\Http\Controllers;

use App\Configurations\PermissionMap;
use App\Configurations\UiNamingConfig;
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
        $baseConfig = new UiNamingConfig();
        $baseConfig = $baseConfig->toJson();
        $config = ConfigController::getValueByKey('ui_naming_config');
        if ($config) {
            return json_encode(array_merge(json_decode($baseConfig, true),json_decode($config, true)));
        } else {
            return $baseConfig;
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
