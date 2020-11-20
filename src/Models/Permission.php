<?php

namespace Qihucms\Role\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name', 'slug', 'amount'
    ];

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            'Qihucms\Role\Models\Role',
            'Qihucms\Role\Models\RolePermission',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            'App\Models\User',
            'Qihucms\Role\Models\UserPermission',
            'permission_id',
            'user_id'
        )->withTimestamps();
    }
}
