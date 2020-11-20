<?php

namespace Qihucms\Role\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    public $incrementing = true;

    protected $table = 'role_users';

    protected $fillable = ['user_id', 'role_id', 'expires'];
}
