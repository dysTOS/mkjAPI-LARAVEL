<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noten extends Model
{
    use HasFactory;

    public function ausrueckung()
    {
        return $this->belongsToMany(Ausrueckung::class,
            'ausrueckung_noten', 'noten_id', 'ausrueckung_id');
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
        'aufbewahrungsort'
    ];
}
