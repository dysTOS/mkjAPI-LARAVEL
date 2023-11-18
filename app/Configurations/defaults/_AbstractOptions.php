<?php

namespace App\Configurations\defaults;

use App\DAO\KeyValueConfigDAO;

abstract class _AbstractOptions
{
    abstract protected function getKey() : string;
    abstract public function get();
    protected function getStoredValue() {
        $key = $this->getKey();
        return KeyValueConfigDAO::getValueByKey($key);
    }

    public function permit($data): void
    {
        $key = $this->getKey();
        KeyValueConfigDAO::storeKeyValue($key, json_encode($data));
    }
}
