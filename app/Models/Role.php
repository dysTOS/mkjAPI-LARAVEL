<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function mitglieder()
    {
        return $this->belongsToMany(Mitglieder::class, 'role_mitglied', 'role_id', 'mitglied_id');
    }

    protected $table = 'roles';

    protected $fillable = [
        'role'
    ];
}
