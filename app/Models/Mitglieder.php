<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitglieder extends Model
{
    use HasFactory;

    protected $table = 'mitglieder';
    protected $fillable = [
        'vorname',
        'zuname',
        'titel_vor',
        'titel_nach',
        'geb_dat',
        'geschlecht',
        'strasse',
        'hausnummer',
        'ort',
        'plz',
        'tel_haupt',
        'tel_mobil',
        'email',
        'beruf',
        'aktiv',
        'eintritt_datum',
        'austritt_datum'
    ];
}
