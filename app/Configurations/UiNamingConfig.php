<?php

namespace App\Configurations;

class UiNamingConfig
{
    private $Termine = 'Termine';
    private $Mitglieder = 'Mitglieder';
    private $RegisterUGruppen = 'Register & Gruppen';
    private $Archiv = 'Archiv';
    private $Noten = 'Noten';
    private $Notenmappen = 'Notenmappen';
    private $Instrumente = 'Instrumente';
    private $Statistiken = 'Statistiken';

    public function toJson() {
        return json_encode(get_object_vars($this));
    }
}
