<?php

namespace App\Configurations\defaults;

class TerminKategorien extends _AbstractOptions
{
    private $options = array(
        [
            'label' => 'Kirchlich',
            'value' => 'kirlich',
        ], [
            'label' => 'Konzert',
            'value' => 'konzert',
        ], [
            'label' => 'Weckruf',
            'value' => 'weckruf',
        ], [
            'label' => 'Probe',
            'value' => 'probe',
        ], [
            'label' => 'Sitzung',
            'value' => 'sitzung',
        ], [
            'label' => 'Sonstige',
            'value' => 'sonstige',
        ],
    );

    protected function getKey(): string
    {
        return 'termin_kategorien';
    }

    public function get()
    {
        $stored = $this->getStoredValue();
        if ($stored) {
            return json_decode($stored);
        } else {
            return $this->options;
        }
    }
}
