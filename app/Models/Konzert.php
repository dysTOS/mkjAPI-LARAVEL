<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konzert extends Model
{
    use HasFactory, Uuid;

    public function noten()
    {
        return $this->belongsToMany(Noten::class, 'konzert_noten',
            'konzert_id', 'noten_id');
    }

    protected $table = 'konzerte';
    protected $fillable = [
        'name',
        'datum',
        'ort'
    ];
}
