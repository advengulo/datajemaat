<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait HasPermissions
{
    /**
     * Get all permissions for the user through their roles.
     */
    public function permissions(): Collection
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
    }

    /**
     * Get all permissions (alias for permissions()).
     */
    public function getAllPermissions(): Collection
    {
        return $this->permissions();
    }

    /**
     * Get all permission slugs for the user.
     */
    public function getPermissionSlugs(): Collection
    {
        return $this->permissions()->pluck('slug')->unique();
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->getPermissionSlugs()->contains($permissionSlug);
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $userPermissions = $this->getPermissionSlugs()->toArray();

        foreach ($permissionSlugs as $slug) {
            if (in_array($slug, $userPermissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $userPermissions = $this->getPermissionSlugs()->toArray();

        foreach ($permissionSlugs as $slug) {
            if (!in_array($slug, $userPermissions)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user can perform an action on a module.
     * Example: canDo('jemaat', 'view') checks for 'jemaat.view' permission
     */
    public function canDo(string $module, string $action): bool
    {
        return $this->hasPermission("{$module}.{$action}");
    }

    /**
     * Check if user can view a module.
     */
    public function canView(string $module): bool
    {
        return $this->canDo($module, 'view');
    }

    /**
     * Check if user can create in a module.
     */
    public function canCreate(string $module): bool
    {
        return $this->canDo($module, 'create');
    }

    /**
     * Check if user can update in a module.
     */
    public function canUpdate(string $module): bool
    {
        return $this->canDo($module, 'update');
    }

    /**
     * Check if user can delete in a module.
     */
    public function canDelete(string $module): bool
    {
        return $this->canDo($module, 'delete');
    }

    /**
     * Check if user can approve in a module.
     */
    public function canApprove(string $module): bool
    {
        return $this->canDo($module, 'approve');
    }

    /**
     * Check if user can export from a module.
     */
    public function canExport(string $module): bool
    {
        return $this->canDo($module, 'export');
    }

    /**
     * Check if user has any admin permissions.
     */
    public function hasAdminAccess(): bool
    {
        return $this->hasPermission('admin.users') ||
               $this->hasPermission('admin.roles') ||
               $this->hasPermission('admin.permissions');
    }
}
