<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Config;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    public static function getValueByKey($key)
        {
            $entry = DB::table('config')->where('key', '=', $key)->first();
            if($entry != null){
                return $entry->value;
            }else{
            return null;
            }
        }

        public static function storeKeyValue($key, $value)
        {
            DB::table('config')->upsert(['key' => $key, 'value' => $value],['key']);
        }

        public static function removeKey($key){
            DB::table('config')->where('key', '=', $key)->delete();
        }
}
