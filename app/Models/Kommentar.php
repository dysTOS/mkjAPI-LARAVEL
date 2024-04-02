<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kommentar extends Model
{
    use HasFactory, Uuid;

    public function commentable()
    {
        return $this->morphTo();
    }

    public function subComments()
    {
        return $this->hasMany(Kommentar::class, 'parent_comment_id');
    }

    protected $table = 'kommentare';
    protected $fillable = [
        'text',
        'mitglied_id',
    ];
}
