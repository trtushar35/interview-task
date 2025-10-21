<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'icon',
        'route',
        'description',
        'sorting',
        'parent_id',
        'permission_name',
        'status',
    ];

    protected $casts = [
        'sorting' => 'integer',
    ];

    /**
     * Get the parent menu
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get the child menus
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sorting');
    }

    /**
     * Scope active menus
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope parent menus (no parent)
     */
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope ordered by sorting
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sorting');
    }
}
