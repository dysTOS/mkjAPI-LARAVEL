<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noten extends Model
{
    use HasFactory, Uuid;

    public function ausrueckung()
    {
        return $this->belongsToMany(Ausrueckung::class,
            'ausrueckung_noten', 'noten_id', 'ausrueckung_id');
    }

    public function mappen()
    {
        return $this->belongsToMany(Notenmappe::class,
            'mappe_noten', 'noten_id', 'mappe_id')->withPivot('verzeichnisNr');
    }

    protected $table = 'noten';
    protected $fillable = [
        'inventarId',
        'titel',
        'komponist',
        'arrangeur',
        'verlag',
        'gattung',
        'ausgeliehenAb',
        'ausgeliehenVon',
        'anmerkungen',
        'aufbewahrungsort',
        'links'
    ];
}
