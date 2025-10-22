<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'number',
        'photo',
        'company_name',
        'address',
        'password',
        'role_id',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? url('storage/' . $this->photo) : null;
    }

    public function permissions()
    {
        return $this->role->permissions();
    }

    public function hasPermission(string $permissionName): bool
    {
        // Admin role has all permissions
        if ($this->role->name === 'Admin') {
            return true;
        }

        return $this->permissions()
            ->where('name', $permissionName)
            ->exists();
    }

    public function hasAnyPermission(array $permissionNames): bool
    {
        // Admin role has all permissions
        if ($this->role->name === 'Admin') {
            return true;
        }

        return $this->permissions()
            ->whereIn('name', $permissionNames)
            ->exists();
    }

    public function hasAllPermissions(array $permissionNames): bool
    {
        // Admin role has all permissions
        if ($this->role->name === 'Admin') {
            return true;
        }

        $userPermissionCount = $this->permissions()
            ->whereIn('name', $permissionNames)
            ->count();

        return $userPermissionCount === count($permissionNames);
    }
}
