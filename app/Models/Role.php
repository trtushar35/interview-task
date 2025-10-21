<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function hasPermission($permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    public function syncPermissions(array $permissionIds)
    {
        return $this->permissions()->sync($permissionIds);
    }

    public function addPermissions(array $permissionIds)
    {
        return $this->permissions()->attach($permissionIds);
    }

    public function removePermissions(array $permissionIds)
    {
        return $this->permissions()->detach($permissionIds);
    }
}
