<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Mitglieder extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class, 'mitglied_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_mitglied', 'mitglied_id', 'role_id');
    }


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
