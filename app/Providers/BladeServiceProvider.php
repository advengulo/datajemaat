<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // @role directive - Check if user has specific role(s)
        // Usage: @role('superadmin') ... @endrole
        // Usage: @role('superadmin|snk') ... @endrole
        Blade::if('role', function ($roles) {
            if (!auth()->check()) {
                return false;
            }

            $roles = is_array($roles) ? $roles : explode('|', $roles);
            return auth()->user()->hasAnyRole($roles);
        });

        // @permission directive - Check if user has specific permission(s)
        // Usage: @permission('jemaat.view') ... @endpermission
        // Usage: @permission('jemaat.create|jemaat.update') ... @endpermission
        Blade::if('permission', function ($permissions) {
            if (!auth()->check()) {
                return false;
            }

            $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);
            return auth()->user()->hasAnyPermission($permissions);
        });

        // @canseemenu directive - Check if user can see a menu based on module permissions
        // Usage: @canseemenu('jemaat') ... @endcanseemenu
        Blade::if('canseemenu', function ($module) {
            if (!auth()->check()) {
                return false;
            }

            $user = auth()->user();

            // Superadmin can see all menus
            if ($user->isSuperAdmin()) {
                return true;
            }

            // Get all user permissions
            $permissions = collect($user->getPermissionSlugs());

            // Check if user has any permission related to this module
            // For example, if module is 'jemaat', check for permissions like 'jemaat.view', 'jemaat.create', etc.
            return $permissions->contains(function ($permission) use ($module) {
                return str_starts_with($permission, $module . '.');
            });
        });
    }
}
