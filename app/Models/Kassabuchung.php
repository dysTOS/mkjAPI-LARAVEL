<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kassabuchung extends Model
{
    use HasFactory, Uuid;

    protected $table = 'kassabuchungen';
    protected $fillable = [
        'typ',
        'nummer',
        'datum',
        'gesamtpreis',
        'bezahlt',
        'positionen',
        'konditionen',
        'anmerkungen',
        'anschrift_id',
    ];
}
