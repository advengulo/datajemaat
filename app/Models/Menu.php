<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'route',
        'parent_id',
        'order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'parent_id' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parent menu.
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get the child menus.
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get the permissions required for this menu.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'menu_permissions', 'menu_id', 'permission_id')
                    ->withTimestamps();
    }

    /**
     * Check if menu requires any of the given permissions.
     *
     * @param array $userPermissions
     * @return bool
     */
    public function isAccessibleBy(array $userPermissions)
    {
        $requiredPermissions = $this->permissions()->pluck('slug')->toArray();

        // If no permissions required, menu is accessible to all
        if (empty($requiredPermissions)) {
            return true;
        }

        // User needs at least one of the required permissions
        return count(array_intersect($userPermissions, $requiredPermissions)) > 0;
    }

    /**
     * Scope a query to only include active menus.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include parent menus (no parent).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to only include child menus (has parent).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Get menus ordered by hierarchy.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get menu tree structure for a user based on their permissions.
     *
     * @param array $userPermissions
     * @return \Illuminate\Support\Collection
     */
    public static function getMenuTreeForUser(array $userPermissions)
    {
        $menus = static::active()->with('permissions')->ordered()->get();

        $accessibleMenus = $menus->filter(function ($menu) use ($userPermissions) {
            return $menu->isAccessibleBy($userPermissions);
        });

        return $accessibleMenus->whereNull('parent_id')->map(function ($menu) use ($accessibleMenus) {
            $menu->children = $accessibleMenus->where('parent_id', $menu->id)->values();
            return $menu;
        })->values();
    }
}
