<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasFactory, Uuid;

    public function mitglied()
    {
        return $this->belongsTo(Mitglieder::class, 'instrument_id');
    }

    protected $table = 'instrumente';
    protected $fillable = [
        'marke',
        'bezeichnung',
        'anschaffungsdatum',
        'verkaeufer',
        'anmerkungen',
        'schaeden',
        'aufbewahrungsort',
    ];
}
