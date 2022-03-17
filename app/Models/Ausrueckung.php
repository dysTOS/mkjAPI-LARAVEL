<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ausrueckung extends Model
{
    use HasFactory;

    public function noten()
    {
        return $this->belongsToMany(Noten::class, 'ausrueckung_noten',
            'ausrueckung_id', 'noten_id');
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
        'von',
        'bis'
    ];
}
