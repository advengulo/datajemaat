<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {
            // Clear existing role-permission mappings (already cleared by RolesSeeder)
            // Get roles
            $superadmin = Role::where('slug', 'superadmin')->first();
            $snk = Role::where('slug', 'snk')->first();
            $lingkunganAdmin = Role::where('slug', 'lingkungan_admin')->first();

            if (!$superadmin || !$snk || !$lingkunganAdmin) {
                throw new \Exception('Roles not found. Please run RolesSeeder first.');
            }

            // Assign ALL permissions to Super Admin
            $allPermissions = Permission::all()->pluck('id');
            $superadmin->permissions()->attach($allPermissions);

            // Assign permissions to SNK
            $snkPermissionSlugs = [
                // Jemaat - view, update (draft only), and export
                'jemaat.view',
                'jemaat.update',
                'jemaat.export',

                // Simpatisan - view and update (draft only)
                'simpatisan.view',
                'simpatisan.update',

                // Kartu Jemaat - view and print
                'kartu_jemaat.view',
                'kartu_jemaat.print',

                // Reports
                'reports.view',
                'grafik.view',
                'rekap.view',
            ];

            $snkPermissions = Permission::whereIn('slug', $snkPermissionSlugs)->pluck('id');
            $snk->permissions()->attach($snkPermissions);

            // Assign permissions to Lingkungan Admin
            $lingkunganAdminPermissionSlugs = [
                // Jemaat - view, create, update (scoped to lingkungan, draft only)
                'jemaat.view',
                'jemaat.create',
                'jemaat.update',

                // Simpatisan - view, create, update (scoped to lingkungan, draft only)
                'simpatisan.view',
                'simpatisan.create',
                'simpatisan.update',

                // Reports (scoped to their lingkungan)
                'reports.view',
                'grafik.view',
                'rekap.view',
            ];

            $lingkunganAdminPermissions = Permission::whereIn('slug', $lingkunganAdminPermissionSlugs)->pluck('id');
            $lingkunganAdmin->permissions()->attach($lingkunganAdminPermissions);

            DB::commit();

            $this->command->info('✓ Role-Permission mappings seeded successfully');
            $this->command->info("  - Super Admin: {$allPermissions->count()} permissions");
            $this->command->info("  - SNK: {$snkPermissions->count()} permissions");
            $this->command->info("  - Lingkungan Admin: {$lingkunganAdminPermissions->count()} permissions");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('✗ Error seeding role-permission mappings: ' . $e->getMessage());
            throw $e;
        }
    }
}
