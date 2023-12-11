<?php

namespace App\Configurations\defaults;

class UiNamingConfig extends _AbstractOptions
{
    private $options = [
        'Termine' => 'Termine',
        'Mitglieder' => 'Mitglieder',
        'Instrumente' => 'Instrumente',
        'Register & Gruppen' => 'Register & Gruppen',
        'Archiv' => 'Archiv',
        'Noten' => 'Noten',
        'Notenmappen' => 'Notenmappen',
        'Statistiken' => 'Statistiken',
        'Finanzen' => 'Finanzen',
    ];

    protected function getKey(): string
    {
        return 'ui_naming_config';
    }

    public function get()
    {
        $stored = $this->getStoredValue();
        if ($stored) {
            return array_merge($this->options, json_decode($stored, true) );
        } else {
            return $this->options;
        }
    }
}
