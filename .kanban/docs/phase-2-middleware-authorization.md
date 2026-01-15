# Phase 2: Middleware & Authorization Documentation

## Overview
Implement middleware for role/permission checks, Blade directives for UI authorization, and route protection.

---

## Middleware Architecture

### 1. CheckRole Middleware
**Purpose:** Verify user has required role(s)
**Usage:** `->middleware('role:superadmin')` or `->middleware('role:superadmin|snk')`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            abort(401, 'Unauthenticated.');
        }

        if (!$request->user()->hasRole($roles)) {
            abort(403, 'Unauthorized. Required role: ' . implode(' or ', $roles));
        }

        return $next($request);
    }
}
```

---

### 2. CheckPermission Middleware
**Purpose:** Verify user has required permission(s)
**Usage:** `->middleware('permission:jemaat.view')` or `->middleware('permission:jemaat.view|jemaat.create')`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!$request->user()) {
            abort(401, 'Unauthenticated.');
        }

        if (!$request->user()->hasAnyPermission($permissions)) {
            abort(403, 'Unauthorized. Required permission: ' . implode(' or ', $permissions));
        }

        return $next($request);
    }
}
```

---

### 3. ScopeLingkungan Middleware
**Purpose:** Apply global scope to queries for `lingkungan_admin` users
**Usage:** `->middleware('scope.lingkungan')`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\data_jemaat;
use Illuminate\Http\Request;

class ScopeLingkungan
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Skip for superadmin or unauthenticated
        if (!$user || $user->hasRole('superadmin')) {
            return $next($request);
        }

        // Apply scope for lingkungan_admin
        if ($user->hasRole('lingkungan_admin')) {
            $lingkunganIds = $user->lingkungans->pluck('id')->toArray();

            if (empty($lingkunganIds)) {
                abort(403, 'No lingkungan assigned to your account.');
            }

            // Apply global scope to data_jemaat queries
            data_jemaat::addGlobalScope('lingkungan', function ($query) use ($lingkunganIds) {
                $query->whereIn('id_lingkungan', $lingkunganIds);
            });
        }

        return $next($request);
    }
}
```

**Important:** This middleware should run early in the middleware stack.

---

## Middleware Registration

### File: `app/Http/Kernel.php`

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ... existing global middleware
    ];

    protected $middlewareGroups = [
        'web' => [
            // ... existing web middleware
        ],

        'api' => [
            // ... existing api middleware
        ],
    ];

    protected $routeMiddleware = [
        // ... existing route middleware

        // RBAC middleware
        'role' => \App\Http\Middleware\CheckRole::class,
        'permission' => \App\Http\Middleware\CheckPermission::class,
        'scope.lingkungan' => \App\Http\Middleware\ScopeLingkungan::class,
    ];
}
```

---

## Blade Directives

### BladeServiceProvider
**File:** `app/Providers/BladeServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // @role('superadmin') ... @endrole
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        // @permission('jemaat.view') ... @endpermission
        Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        // @canseemenu('jemaat') ... @endcanseemenu
        Blade::if('canseemenu', function ($menuSlug) {
            if (!auth()->check()) {
                return false;
            }

            $menu = \App\Models\Menu::where('slug', $menuSlug)->first();
            if (!$menu || !$menu->is_active) {
                return false;
            }

            // If menu has no permissions, anyone can see it
            if ($menu->permissions->isEmpty()) {
                return true;
            }

            // User needs at least one of the menu's permissions
            $permissionSlugs = $menu->permissions->pluck('slug')->toArray();
            return auth()->user()->hasAnyPermission($permissionSlugs);
        });
    }
}
```

### Register Provider
**File:** `config/app.php`

```php
'providers' => [
    // ... existing providers
    App\Providers\BladeServiceProvider::class,
],
```

---

## Route Protection Examples

### File: `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Jemaat routes - with lingkungan scoping
    Route::middleware(['scope.lingkungan'])->group(function () {

        // View jemaat - requires jemaat.view permission
        Route::get('/data-jemaat', [App\Http\Controllers\DataJemaatController::class, 'index'])
            ->middleware('permission:jemaat.view')
            ->name('data-jemaat.index');

        // Create jemaat - requires jemaat.create permission
        Route::get('/tambah-jemaat', [App\Http\Controllers\DataJemaatController::class, 'create'])
            ->middleware('permission:jemaat.create,jemaat.update')
            ->name('tambah-jemaat.index');

        Route::post('/tambah-jemaat', [App\Http\Controllers\DataJemaatController::class, 'store'])
            ->middleware('permission:jemaat.create,jemaat.update');
    });

    // Admin routes - superadmin only
    Route::prefix('admin')->middleware(['role:superadmin'])->group(function () {

        // User management
        Route::get('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])
            ->name('admin.users.index');

        Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])
            ->name('admin.users.edit');

        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])
            ->name('admin.users.update');

        // Role management
        Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);

        // Draft approvals
        Route::get('/drafts/pending', [App\Http\Controllers\Admin\ApprovalController::class, 'pending'])
            ->name('admin.drafts.pending');

        Route::post('/drafts/{draft}/approve', [App\Http\Controllers\Admin\ApprovalController::class, 'approve'])
            ->name('admin.drafts.approve');

        Route::post('/drafts/{draft}/reject', [App\Http\Controllers\Admin\ApprovalController::class, 'reject'])
            ->name('admin.drafts.reject');
    });
});
```

---

## Blade Template Usage

### Sidebar Menu Example
**File:** `resources/views/layouts/app.blade.php`

```blade
<aside class="sidebar">
    <ul class="main-menu">

        {{-- Jemaat Menu --}}
        @canseemenu('jemaat')
        <li>
            <a class="has-arrow" href="#" aria-expanded="false">
                <span class="fas fa-user-friends fa-fw"></span>
                <span class="mini-click-non">Jemaat</span>
            </a>
            <ul class="submenu-angle" aria-expanded="false">
                @permission('jemaat.view')
                <li class="{{Request::is('data-jemaat')?'active':''}}">
                    <a href="{{route('data-jemaat.index')}}">Data Jemaat</a>
                </li>
                @endpermission

                @permission('jemaat.create|jemaat.update')
                <li class="{{Request::is('tambah-jemaat')?'active':''}}">
                    <a href="{{route('tambah-jemaat.index')}}">Tambah Jemaat</a>
                </li>
                @endpermission
            </ul>
        </li>
        @endcanseemenu

        {{-- Simpatisan Menu --}}
        @canseemenu('simpatisan')
        <li>
            <a class="has-arrow" href="#">
                <span class="fas fa-users fa-fw"></span>
                <span class="mini-click-non">Simpatisan</span>
            </a>
            <ul class="submenu-angle">
                @permission('simpatisan.view')
                <li><a href="{{route('simpatisan.index')}}">Data Simpatisan</a></li>
                @endpermission

                @permission('simpatisan.create|simpatisan.update')
                <li><a href="{{route('simpatisan.create')}}">Tambah Simpatisan</a></li>
                @endpermission
            </ul>
        </li>
        @endcanseemenu

        {{-- Admin Menu - Superadmin Only --}}
        @role('superadmin')
        <li>
            <a class="has-arrow" href="#">
                <span class="fas fa-cog fa-fw"></span>
                <span class="mini-click-non">Admin</span>
                @if($pendingDraftsCount > 0)
                <span class="badge badge-warning">{{ $pendingDraftsCount }}</span>
                @endif
            </a>
            <ul class="submenu-angle">
                <li><a href="{{route('admin.users.index')}}">Manage Users</a></li>
                <li><a href="{{route('admin.roles.index')}}">Manage Roles</a></li>
                <li>
                    <a href="{{route('admin.drafts.pending')}}">
                        Pending Approvals
                        @if($pendingDraftsCount > 0)
                        <span class="badge badge-danger">{{ $pendingDraftsCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </li>
        @endrole

    </ul>
</aside>
```

---

## View Composer for Pending Drafts Count

### File: `app/Providers/AppServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\JemaatDraft;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share pending drafts count with all views
        View::composer('*', function ($view) {
            if (auth()->check() && auth()->user()->hasRole('superadmin')) {
                $pendingDraftsCount = JemaatDraft::where('status', 'pending_review')->count();
                $view->with('pendingDraftsCount', $pendingDraftsCount);
            } else {
                $view->with('pendingDraftsCount', 0);
            }
        });
    }
}
```

---

## Authorization in Controllers

### Example: DataJemaatController

```php
<?php

namespace App\Http\Controllers;

use App\Models\data_jemaat;
use App\Services\DataJemaatService;
use Illuminate\Http\Request;

class DataJemaatController extends Controller
{
    private DataJemaatService $service;

    public function __construct(DataJemaatService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // Middleware already checked permission and applied scope
        // Just fetch data
        $jemaat = data_jemaat::paginate(50);
        return view('data-jemaat.index', compact('jemaat'));
    }

    public function store(Request $request)
    {
        // Service layer will check if user can publish directly
        // or if draft workflow is needed
        $result = $this->service->storeDataJemaat($request->all());

        if ($result['isDraft']) {
            return redirect()->route('data-jemaat.index')
                ->with('info', 'Data submitted for approval.');
        }

        return redirect()->route('data-jemaat.index')
            ->with('success', 'Jemaat created successfully.');
    }
}
```

---

## Testing Authorization

### Manual Test Checklist

**As Superadmin:**
- [ ] Can see all menus
- [ ] Can access all routes
- [ ] Can see data from all lingkungan
- [ ] Can directly create/update without draft

**As SNK:**
- [ ] Can see jemaat menu
- [ ] Cannot see admin menu
- [ ] Can view jemaat data (all lingkungan)
- [ ] Create/update creates draft for approval

**As Lingkungan Admin:**
- [ ] Can see jemaat menu
- [ ] Cannot see admin menu
- [ ] Can only see jemaat from assigned lingkungan
- [ ] Cannot see other lingkungan data
- [ ] Create/update creates draft for approval

**Middleware Protection:**
- [ ] Accessing admin route as SNK returns 403
- [ ] Accessing jemaat.create without permission returns 403
- [ ] Unauthenticated user accessing protected route redirects to login
