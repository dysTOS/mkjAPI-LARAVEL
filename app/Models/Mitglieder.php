<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Mitglieder extends Model
{
    use HasFactory, Uuids;

    public function user()
    {
        return $this->hasOne(User::class, 'mitglied_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,
            'role_mitglied', 'mitglied_id', 'role_id');
    }

    public function ausrueckungen()
    {
        return $this->belongsToMany(Ausrueckung::class,
            'ausrueckung_mitglied', 'mitglied_id', 'ausrueckung_id');
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
