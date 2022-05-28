<?php
namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
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
