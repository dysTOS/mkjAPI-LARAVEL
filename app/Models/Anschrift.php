<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anschrift extends Model
{
    use HasFactory, Uuid;

    protected $table = 'anschriften';
    protected $fillable = [
        'vorname',
        'zuname',
        'anrede',
        'titelVor',
        'titelNach',
        'geburtsdatum',
        'firma',
        'strasse',
        'hausnummer',
        'ort',
        'plz',
        'email',
        'telefonHaupt',
        'telefonMobil',
        'IBAN',
        'BIC',
    ];
}
