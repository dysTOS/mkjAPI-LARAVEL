<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ausrueckung extends Model
{
    use HasFactory, Uuid;

    public function noten()
    {
        return $this->belongsToMany(Noten::class, 'ausrueckung_noten',
            'ausrueckung_id', 'noten_id');
    }

    public function mitglieder()
    {
        return $this->belongsToMany(Mitglieder::class, 'ausrueckung_mitglied',
            'ausrueckung_id', 'mitglied_id');
    }

    public function gruppe()
    {
        return $this->belongsTo(Gruppe::class);
    }

    protected $table = 'ausrueckungen';
    protected $fillable = [
        'name',
        'beschreibung',
        'infoMusiker',
        'oeffentlich',
        'kategorie',
        'ort',
        'treffzeit',
        'status',
        'vonDatum',
        'bisDatum',
        'vonZeit',
        'bisZeit',
        'gruppe_id'
    ];
}
