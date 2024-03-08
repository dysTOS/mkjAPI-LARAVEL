<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bewertung extends Model
{
    use HasFactory, Uuid;

    public function bewertbar()
    {
        return $this->morphTo();
    }

    protected $table = 'bewertungen';
    protected $fillable = [
        'bewertung',
        'mitglied_id',
    ];
}
