<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'module',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the roles that have this permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id')
                    ->withTimestamps();
    }

    /**
     * Get the menus that require this permission.
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_permissions', 'permission_id', 'menu_id')
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include permissions for a specific module.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $module
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope a query to only include permissions with a specific slug.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get all permissions grouped by module.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function groupedByModule()
    {
        return static::orderBy('module')->orderBy('name')->get()->groupBy('module');
    }
}
