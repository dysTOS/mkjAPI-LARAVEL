<?php

namespace App\Configurations\defaults;

class NotenGattungen extends _AbstractOptions
{
    private $options = array(
        [
            'label' => 'Charakterstück',
            'value' => 'Charakterstück',
        ], [
            'label' => 'Diverse',
            'value' => 'Diverse',
        ], [
            'label' => 'Eröffnungsmusik',
            'value' => 'Eröffnungsmusik',
        ], [
            'label' => 'Filmmusik',
            'value' => 'Filmmusik',
        ], [
            'label' => 'Intermezzo',
            'value' => 'Intermezzo',
        ], [
            'label' => 'Kirchenmusik',
            'value' => 'Kirchenmusik',
        ], [
            'label' => 'Lied',
            'value' => 'Lied',
        ],[
            'label' => 'Marsch',
            'value' => 'Marsch',
        ],[
            'label' => 'Ouvertüre',
            'value' => 'Ouvertüre',
        ],[
            'label' => 'Polka',
            'value' => 'Polka',
        ],[
            'label' => 'Potpourrie/Medley',
            'value' => 'Potpourrie/Medley',
        ],[
            'label' => 'Quartett',
            'value' => 'Quartett',
        ],[
            'label' => 'Sololiteratur',
            'value' => 'Sololiteratur',
        ],[
            'label' => 'Trauermusik',
            'value' => 'Trauermusik',
        ],[
            'label' => 'Walzer',
            'value' => 'Walzer',
        ],[
            'label' => 'Wiener Tanzmusik',
            'value' => 'Wiener Tanzmusik',
        ],
    );

    protected function getKey(): string
    {
        return 'noten_gattungen';
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
