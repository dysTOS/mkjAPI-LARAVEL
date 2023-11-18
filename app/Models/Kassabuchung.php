<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kassabuchung extends Model
{
    use HasFactory, Uuid;

    public function kassabuch()
    {
        return $this->belongsTo(Kassabuch::class, 'kassabuch_id', 'id');
    }

    public function anschrift()
    {
        return $this->hasOne(Anschrift::class, 'id', 'anschrift_id');
    }

    protected $table = 'kassabuchungen';
    protected $fillable = [
        'typ',
        'nummer',
        'datum',
        'gesamtpreis',
        'bezahltDatum',
        'positionen',
        'konditionen',
        'anmerkungen',
        'anschrift_id',
        'kassabuch_id'
    ];
}
