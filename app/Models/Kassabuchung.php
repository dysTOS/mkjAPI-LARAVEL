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
        return $this->belongsTo(Kassabuch::class, 'kassabuch_id');
    }

    public function anschrift()
    {
        return $this->belongsTo(Anschrift::class,  'anschrift_id');
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

    protected $casts = [
        'positionen' => 'array',
        'konditionen' => 'array'
    ];
}
