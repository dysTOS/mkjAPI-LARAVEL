<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notenmappe extends Model
{
    use HasFactory, Uuid;

    public function noten()
    {
        return $this->belongsToMany(Noten::class, 'mappe_noten',
            'mappe_id', 'noten_id')->withPivot(['verzeichnisNr'])->withTimestamps();
    }

    protected $table = 'notenmappen';
    protected $fillable = [
        'name',
        'hatVerzeichnis',
        'color'
    ];
}
