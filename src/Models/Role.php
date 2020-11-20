<?php

namespace Qihucms\Role\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name', 'slug', 'desc', 'times', 'unit', 'is_qualification_pa', 'is_qualification_co',
        'price', 'currency_type_id'
    ];

    protected $casts = [
        'times' => 'integer',
        'price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->users()->detach();
            $model->permissions()->detach();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency_type()
    {
        return $this->belongsTo('Qihucms\Currency\Models\CurrencyType', 'currency_type_id');
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            'App\Models\User',
            'Qihucms\Role\Models\RoleUser',
            'role_id',
            'user_id'
        )->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            'Qihucms\Role\Models\Permission',
            'Qihucms\Role\Models\RolePermission',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function can(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function cannot(string $permission): bool
    {
        return !$this->can($permission);
    }
}
