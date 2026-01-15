<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    /**
     * A user can have multiple roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\Role::class,
            'user_roles',
            'user_id',
            'role_id'
        )->withTimestamps();
    }

    /**
     * Check if user has a specific role by slug.
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roleSlugs): bool
    {
        return $this->roles()->whereIn('slug', $roleSlugs)->exists();
    }

    /**
     * Check if user has all of the given roles.
     */
    public function hasAllRoles(array $roleSlugs): bool
    {
        $userRoleSlugs = $this->roles()->pluck('slug')->toArray();

        foreach ($roleSlugs as $slug) {
            if (!in_array($slug, $userRoleSlugs)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole(string $roleSlug): void
    {
        $role = \App\Models\Role::where('slug', $roleSlug)->firstOrFail();

        if (!$this->roles()->where('role_id', $role->id)->exists()) {
            $this->roles()->attach($role->id);
        }
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole(string $roleSlug): void
    {
        $role = \App\Models\Role::where('slug', $roleSlug)->first();

        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Sync user roles (removes all existing and assigns new ones).
     */
    public function syncRoles(array $roleSlugs): void
    {
        $roleIds = \App\Models\Role::whereIn('slug', $roleSlugs)->pluck('id')->toArray();
        $this->roles()->sync($roleIds);
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Check if user is SNK.
     */
    public function isSnk(): bool
    {
        return $this->hasRole('snk');
    }

    /**
     * Check if user is Lingkungan Admin.
     */
    public function isLingkunganAdmin(): bool
    {
        return $this->hasRole('lingkungan_admin');
    }
}
