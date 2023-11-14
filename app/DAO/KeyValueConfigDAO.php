<?php


namespace App\DAO;

use Illuminate\Support\Facades\DB;

class KeyValueConfigDAO
{

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
}
