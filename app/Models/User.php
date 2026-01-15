<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the roles assigned to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')
                    ->withPivot('id_lingkungan')
                    ->withTimestamps();
    }

    /**
     * Get all permissions for the user through their roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function permissions()
    {
        return $this->roles->map->permissions->flatten()->unique('id');
    }

    /**
     * Get all permission slugs for the user.
     *
     * @return array
     */
    public function getPermissionSlugs()
    {
        return $this->permissions()->pluck('slug')->toArray();
    }

    /**
     * Check if user has a specific role.
     *
     * @param string $roleSlug
     * @return bool
     */
    public function hasRole($roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    /**
     * Check if user has any of the given roles.
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles)
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Check if user has all of the given roles.
     *
     * @param array $roles
     * @return bool
     */
    public function hasAllRoles(array $roles)
    {
        $userRoles = $this->roles()->pluck('slug')->toArray();
        return count(array_intersect($roles, $userRoles)) === count($roles);
    }

    /**
     * Check if user has a specific permission.
     *
     * @param string $permissionSlug
     * @return bool
     */
    public function hasPermission($permissionSlug)
    {
        return in_array($permissionSlug, $this->getPermissionSlugs());
    }

    /**
     * Check if user has any of the given permissions.
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions)
    {
        $userPermissions = $this->getPermissionSlugs();
        return count(array_intersect($permissions, $userPermissions)) > 0;
    }

    /**
     * Check if user has all of the given permissions.
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions)
    {
        $userPermissions = $this->getPermissionSlugs();
        return count(array_intersect($permissions, $userPermissions)) === count($permissions);
    }

    /**
     * Assign role to user.
     *
     * @param mixed $role
     * @param int|null $lingkunganId
     * @return void
     */
    public function assignRole($role, $lingkunganId = null)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $pivotData = [];
        if ($lingkunganId !== null) {
            $pivotData['id_lingkungan'] = $lingkunganId;
        }

        $this->roles()->syncWithoutDetaching([$role->id => $pivotData]);
    }

    /**
     * Remove role from user.
     *
     * @param mixed $role
     * @return void
     */
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $this->roles()->detach($role);
    }

    /**
     * Sync roles for user.
     *
     * @param array $roles
     * @return void
     */
    public function syncRoles(array $roles)
    {
        $roleIds = [];

        foreach ($roles as $role) {
            if (is_string($role)) {
                $roleModel = Role::where('slug', $role)->firstOrFail();
                $roleIds[] = $roleModel->id;
            } else {
                $roleIds[] = $role;
            }
        }

        $this->roles()->sync($roleIds);
    }

    /**
     * Get the lingkungan IDs the user has access to based on their roles.
     *
     * @return array
     */
    public function getAccessibleLingkunganIds()
    {
        // Super admin and SNK have access to all lingkungan
        if ($this->hasAnyRole(['superadmin', 'snk'])) {
            return master_lingkungan::pluck('id')->toArray();
        }

        // Lingkungan admin only has access to assigned lingkungan
        return $this->roles()
                    ->where('scope', 'lingkungan')
                    ->get()
                    ->pluck('pivot.id_lingkungan')
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();
    }

    /**
     * Check if user has access to a specific lingkungan.
     *
     * @param int $lingkunganId
     * @return bool
     */
    public function hasAccessToLingkungan($lingkunganId)
    {
        return in_array($lingkunganId, $this->getAccessibleLingkunganIds());
    }

    /**
     * Get the user's menu tree based on their permissions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMenuTree()
    {
        return Menu::getMenuTreeForUser($this->getPermissionSlugs());
    }

    /**
     * Check if user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Check if user is SNK.
     *
     * @return bool
     */
    public function isSnk()
    {
        return $this->hasRole('snk');
    }

    /**
     * Check if user is lingkungan admin.
     *
     * @return bool
     */
    public function isLingkunganAdmin()
    {
        return $this->hasRole('lingkungan_admin');
    }
}
