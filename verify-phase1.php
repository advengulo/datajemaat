#!/usr/bin/env php
<?php

/**
 * Phase 1 RBAC Foundation Verification Script
 *
 * This script verifies that all Phase 1 components are in place
 */

echo "\n=== Phase 1: RBAC Foundation Verification ===\n\n";

$errors = [];
$warnings = [];
$success = [];

// Check migrations
echo "Checking Migrations...\n";
$migrations = [
    'database/migrations/2026_01_15_000001_create_roles_table.php',
    'database/migrations/2026_01_15_000002_create_permissions_table.php',
    'database/migrations/2026_01_15_000003_create_role_permissions_table.php',
    'database/migrations/2026_01_15_000004_create_user_roles_table.php',
    'database/migrations/2026_01_15_000005_create_menus_table.php',
    'database/migrations/2026_01_15_000006_create_menu_permissions_table.php',
    'database/migrations/2026_01_15_000007_create_user_lingkungans_table.php',
];

foreach ($migrations as $migration) {
    if (file_exists($migration)) {
        $success[] = "✓ Migration exists: " . basename($migration);
    } else {
        $errors[] = "✗ Migration missing: " . basename($migration);
    }
}

// Check models
echo "\nChecking Models...\n";
$models = [
    'app/Models/Role.php',
    'app/Models/Permission.php',
    'app/Models/Menu.php',
];

foreach ($models as $model) {
    if (file_exists($model)) {
        $success[] = "✓ Model exists: " . basename($model);

        // Check for required methods
        $content = file_get_contents($model);
        $modelName = basename($model, '.php');

        if ($modelName === 'Role') {
            if (strpos($content, 'function permissions()') !== false) {
                $success[] = "  ✓ permissions() relationship found";
            } else {
                $warnings[] = "  ⚠ permissions() relationship not found";
            }
            if (strpos($content, 'function users()') !== false) {
                $success[] = "  ✓ users() relationship found";
            } else {
                $warnings[] = "  ⚠ users() relationship not found";
            }
        }

        if ($modelName === 'Permission') {
            if (strpos($content, 'function roles()') !== false) {
                $success[] = "  ✓ roles() relationship found";
            } else {
                $warnings[] = "  ⚠ roles() relationship not found";
            }
        }

        if ($modelName === 'Menu') {
            if (strpos($content, 'function parent()') !== false) {
                $success[] = "  ✓ parent() relationship found";
            } else {
                $warnings[] = "  ⚠ parent() relationship not found";
            }
            if (strpos($content, 'function children()') !== false) {
                $success[] = "  ✓ children() relationship found";
            } else {
                $warnings[] = "  ⚠ children() relationship not found";
            }
            if (strpos($content, 'function permissions()') !== false) {
                $success[] = "  ✓ permissions() relationship found";
            } else {
                $warnings[] = "  ⚠ permissions() relationship not found";
            }
        }
    } else {
        $errors[] = "✗ Model missing: " . basename($model);
    }
}

// Check traits
echo "\nChecking Traits...\n";
$traits = [
    'app/Traits/HasRoles.php' => ['hasRole', 'assignRole', 'removeRole', 'roles'],
    'app/Traits/HasPermissions.php' => ['hasPermission', 'hasAnyPermission', 'getAllPermissions', 'permissions'],
    'app/Traits/HasLingkunganScope.php' => ['assignLingkungan', 'removeLingkungan', 'hasLingkunganAccess', 'lingkungans'],
];

foreach ($traits as $trait => $methods) {
    if (file_exists($trait)) {
        $success[] = "✓ Trait exists: " . basename($trait);

        $content = file_get_contents($trait);
        foreach ($methods as $method) {
            if (strpos($content, "function $method") !== false) {
                $success[] = "  ✓ Method found: $method()";
            } else {
                $errors[] = "  ✗ Method missing: $method()";
            }
        }
    } else {
        $errors[] = "✗ Trait missing: " . basename($trait);
    }
}

// Check User model for traits
echo "\nChecking User Model...\n";
if (file_exists('app/Models/User.php')) {
    $content = file_get_contents('app/Models/User.php');

    if (strpos($content, 'use HasRoles') !== false) {
        $success[] = "✓ User model uses HasRoles trait";
    } else {
        $errors[] = "✗ User model missing HasRoles trait";
    }

    if (strpos($content, 'use HasPermissions') !== false) {
        $success[] = "✓ User model uses HasPermissions trait";
    } else {
        $errors[] = "✗ User model missing HasPermissions trait";
    }

    if (strpos($content, 'use HasLingkunganScope') !== false) {
        $success[] = "✓ User model uses HasLingkunganScope trait";
    } else {
        $errors[] = "✗ User model missing HasLingkunganScope trait";
    }
} else {
    $errors[] = "✗ User model not found";
}

// Check seeders
echo "\nChecking Seeders...\n";
$seeders = [
    'database/seeds/RolesSeeder.php',
    'database/seeds/PermissionsSeeder.php',
    'database/seeds/RolePermissionsSeeder.php',
    'database/seeds/MenusSeeder.php',
    'database/seeds/MenuPermissionsSeeder.php',
];

foreach ($seeders as $seeder) {
    if (file_exists($seeder)) {
        $success[] = "✓ Seeder exists: " . basename($seeder);
    } else {
        $errors[] = "✗ Seeder missing: " . basename($seeder);
    }
}

// Check DatabaseSeeder
echo "\nChecking DatabaseSeeder Configuration...\n";
if (file_exists('database/seeds/DatabaseSeeder.php')) {
    $content = file_get_contents('database/seeds/DatabaseSeeder.php');

    $requiredSeeders = [
        'RolesSeeder',
        'PermissionsSeeder',
        'RolePermissionsSeeder',
        'MenusSeeder',
        'MenuPermissionsSeeder',
    ];

    foreach ($requiredSeeders as $seederName) {
        if (strpos($content, $seederName . '::class') !== false) {
            $success[] = "✓ DatabaseSeeder calls: $seederName";
        } else {
            $errors[] = "✗ DatabaseSeeder missing call to: $seederName";
        }
    }
} else {
    $errors[] = "✗ DatabaseSeeder not found";
}

// Print results
echo "\n=== Verification Results ===\n\n";

if (!empty($success)) {
    echo "SUCCESS (" . count($success) . " items):\n";
    foreach ($success as $msg) {
        echo "$msg\n";
    }
}

if (!empty($warnings)) {
    echo "\nWARNINGS (" . count($warnings) . " items):\n";
    foreach ($warnings as $msg) {
        echo "$msg\n";
    }
}

if (!empty($errors)) {
    echo "\nERRORS (" . count($errors) . " items):\n";
    foreach ($errors as $msg) {
        echo "$msg\n";
    }
}

echo "\n=== Summary ===\n";
echo "Total Success: " . count($success) . "\n";
echo "Total Warnings: " . count($warnings) . "\n";
echo "Total Errors: " . count($errors) . "\n";

if (empty($errors)) {
    echo "\n✓ Phase 1 RBAC Foundation is COMPLETE!\n";
    echo "\nNext Steps:\n";
    echo "1. Configure your .env file with database credentials\n";
    echo "2. Run: php artisan migrate\n";
    echo "3. Run: php artisan db:seed\n";
    echo "4. Test the User model methods in tinker\n";
    exit(0);
} else {
    echo "\n✗ Phase 1 RBAC Foundation has issues that need to be resolved.\n";
    exit(1);
}
