<?php

namespace App\Configurations\defaults;

class UiNamingConfig extends _AbstractOptions
{
    private $options = [
        'Archiv' => 'Archiv',
        'Anschrift' => 'Adresse',
        'Anschriften' => 'Adressen',
        'Finanzen' => 'Finanzen',
        'Gruppen' => 'Register & Gruppen',
        'Instrumente' => 'Instrumente',
        'Mitglieder' => 'Mitglieder',
        'Noten' => 'Noten',
        'Notengattung' => 'Gattung',
        'Notenmappen' => 'Notenmappen',
        'Statistiken' => 'Statistiken',
        'Termine' => 'Termine',
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
