<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anschrift extends Model
{
    use HasFactory, Uuid;

    public function kassabuchungen()
    {
        return $this->hasMany(Kassabuchung::class);
    }

    public function mitglied()
    {
        return $this->hasOne(Mitglieder::class, 'anschrift_id');
    }

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
