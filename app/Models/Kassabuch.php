<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kassabuch extends Model
{
    use HasFactory, Uuid;

    public function gruppe()
    {
        return $this->belongsTo(Gruppe::class);
    }

    public function kassabuchungen()
    {
        return $this->hasMany(Kassabuchung::class, 'kassabuch_id');
    }

    protected $table = 'kassabuch';
    protected $fillable = [
        'name',
        'aktiv',
        'anmerkungen',
        'kassastand',
        'color',
        'gruppe_id',
    ];
}
