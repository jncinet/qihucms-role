<?php

namespace Qihucms\Role\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPermission extends Pivot
{
    protected $table = 'user_permissions';

    public $incrementing = true;

    protected $fillable = ['user_id', 'permission_id'];
}