<?php
namespace App\Traits;

use App\Models\Kommentar;

trait Commentable
{

    public function comments()
    {
        return $this->morphMany(Kommentar::class, 'commentable')->latest();
    }
}
