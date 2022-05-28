<?php
namespace App\Models;

use App\Traits\Uuid;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
use Uuid;
public $incrementing = false;
protected $keyType = 'string';

/**
* The attributes that should be cast to native types.
*
* @var array
*/
protected $casts = [
'id' => 'string'
];
}
