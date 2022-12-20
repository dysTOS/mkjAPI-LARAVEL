<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory, Uuid;

    public function mitglied()
    {
        return $this->hasOne(Mitglieder::class, 'id', 'mitglied_id');
    }

    protected $table = 'reports';
    protected $fillable = [
        'title',
        'type',
        'json',

    ];
}
