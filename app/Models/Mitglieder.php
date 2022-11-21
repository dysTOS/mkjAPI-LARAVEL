<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Mitglieder extends Model
{
    use HasFactory, Uuid;

    public function user()
    {
        return $this->hasOne(User::class, 'mitglied_id');
    }

    public function ausrueckungen()
    {
        return $this->belongsToMany(Ausrueckung::class,
            'ausrueckung_mitglied', 'mitglied_id', 'ausrueckung_id');
    }

    public function gruppen()
    {
        return $this->belongsToMany(Gruppe::class, 'mitglied_gruppe', 'mitglied_id', 'gruppen_id')->withTimestamps();
    }


    protected $table = 'mitglieder';
    protected $fillable = [
        'vorname',
        'zuname',
        'titelVor',
        'titelNach',
        'geburtsdatum',
        'geschlecht',
        'strasse',
        'hausnummer',
        'ort',
        'plz',
        'telefonHaupt',
        'telefonMobil',
        'email',
        'beruf',
        'aktiv',
        'eintrittDatum',
        'austrittDatum'
    ];
}
