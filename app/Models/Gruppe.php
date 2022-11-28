<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Gruppe extends Model
{
    use HasFactory, Uuid;

    public function gruppenleiter()
    {
        return $this->hasOne(Mitglieder::class, 'id', 'gruppenleiter_mitglied_id');
    }

    public function mitglieder()
    {
        return $this->belongsToMany(Mitglieder::class,
            'mitglied_gruppe', 'gruppen_id', 'mitglied_id')->withTimestamps();
    }

    public function ausrueckungen()
    {
        return $this->hasMany(Ausrueckung::class, 'gruppe_id');
    }



    protected $table = 'gruppen';
    protected $fillable = [
        'name',
        'gruppenleiter_mitglied_id',
        'color'
    ];
}
