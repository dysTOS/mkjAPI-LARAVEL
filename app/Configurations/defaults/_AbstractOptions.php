<?php

namespace App\Configurations\defaults;

use App\DAO\KeyValueConfigDAO;

abstract class _AbstractOptions
{
    abstract protected function getKey() : string;

    protected function getStoredValue() {
        $key = $this->getKey();
        return KeyValueConfigDAO::getValueByKey($key);
    }
    abstract public function get();

    public function permit($data): void
    {
        $key = $this->getKey();
        KeyValueConfigDAO::storeKeyValue($key, json_encode($data));
    }

    public function toJson() : mixed {
        return json_encode($this->options);
    }
}
