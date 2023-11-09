<?php

namespace App\Constants;

class UiNamingConfig
{
    private $Termine = 'Termine';
    private $Noten = 'Noten';

    public function toJson() {
        return json_encode(get_object_vars($this));
    }
}
