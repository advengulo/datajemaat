<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RbacSeeder extends Seeder
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
            // Create Roles
            $superadmin = Role::create([
                'name' => 'Super Admin',
                'slug' => 'superadmin',
                'description' => 'Full access with approval rights',
                'scope' => 'global',
            ]);

            $snk = Role::create([
                'name' => 'SNK',
                'slug' => 'snk',
                'description' => 'View and edit draft data only',
                'scope' => 'global',
            ]);

            $lingkunganAdmin = Role::create([
                'name' => 'Lingkungan Admin',
                'slug' => 'lingkungan_admin',
                'description' => 'Scoped to assigned lingkungan',
                'scope' => 'lingkungan',
            ]);

            // Create Permissions
            $permissions = [];

            // Jemaat permissions
            $permissions[] = Permission::create(['name' => 'View Jemaat', 'slug' => 'jemaat.view', 'module' => 'jemaat', 'description' => 'View church members data']);
            $permissions[] = Permission::create(['name' => 'Create Jemaat', 'slug' => 'jemaat.create', 'module' => 'jemaat', 'description' => 'Create new church member']);
            $permissions[] = Permission::create(['name' => 'Update Jemaat', 'slug' => 'jemaat.update', 'module' => 'jemaat', 'description' => 'Update church member data']);
            $permissions[] = Permission::create(['name' => 'Delete Jemaat', 'slug' => 'jemaat.delete', 'module' => 'jemaat', 'description' => 'Delete church member']);
            $permissions[] = Permission::create(['name' => 'Approve Jemaat', 'slug' => 'jemaat.approve', 'module' => 'jemaat', 'description' => 'Approve church member data changes']);
            $permissions[] = Permission::create(['name' => 'Export Jemaat', 'slug' => 'jemaat.export', 'module' => 'jemaat', 'description' => 'Export church member data']);

            // Simpatisan permissions
            $permissions[] = Permission::create(['name' => 'View Simpatisan', 'slug' => 'simpatisan.view', 'module' => 'simpatisan', 'description' => 'View sympathizer data']);
            $permissions[] = Permission::create(['name' => 'Create Simpatisan', 'slug' => 'simpatisan.create', 'module' => 'simpatisan', 'description' => 'Create new sympathizer']);
            $permissions[] = Permission::create(['name' => 'Update Simpatisan', 'slug' => 'simpatisan.update', 'module' => 'simpatisan', 'description' => 'Update sympathizer data']);
            $permissions[] = Permission::create(['name' => 'Delete Simpatisan', 'slug' => 'simpatisan.delete', 'module' => 'simpatisan', 'description' => 'Delete sympathizer']);
            $permissions[] = Permission::create(['name' => 'Approve Simpatisan', 'slug' => 'simpatisan.approve', 'module' => 'simpatisan', 'description' => 'Approve sympathizer data changes']);

            // Kartu Jemaat permissions
            $permissions[] = Permission::create(['name' => 'View Kartu Jemaat', 'slug' => 'kartu_jemaat.view', 'module' => 'kartu_jemaat', 'description' => 'View member cards']);
            $permissions[] = Permission::create(['name' => 'Print Kartu Jemaat', 'slug' => 'kartu_jemaat.print', 'module' => 'kartu_jemaat', 'description' => 'Print member cards']);

            // Master data permissions
            $permissions[] = Permission::create(['name' => 'Manage Lingkungan', 'slug' => 'master.lingkungan', 'module' => 'master', 'description' => 'Manage neighborhood master data']);
            $permissions[] = Permission::create(['name' => 'Manage Pekerjaan', 'slug' => 'master.pekerjaan', 'module' => 'master', 'description' => 'Manage job types master data']);
            $permissions[] = Permission::create(['name' => 'Manage Pendidikan', 'slug' => 'master.pendidikan', 'module' => 'master', 'description' => 'Manage education levels master data']);

            // Reports permissions
            $permissions[] = Permission::create(['name' => 'View Reports', 'slug' => 'reports.view', 'module' => 'reports', 'description' => 'View various reports']);
            $permissions[] = Permission::create(['name' => 'View Grafik', 'slug' => 'grafik.view', 'module' => 'grafik', 'description' => 'View statistical graphs']);
            $permissions[] = Permission::create(['name' => 'View Rekap', 'slug' => 'rekap.view', 'module' => 'rekap', 'description' => 'View data recaps']);

            // Admin permissions
            $permissions[] = Permission::create(['name' => 'Manage Roles', 'slug' => 'admin.roles', 'module' => 'admin', 'description' => 'Manage user roles']);
            $permissions[] = Permission::create(['name' => 'Manage Permissions', 'slug' => 'admin.permissions', 'module' => 'admin', 'description' => 'Manage permissions']);
            $permissions[] = Permission::create(['name' => 'Manage Users', 'slug' => 'admin.users', 'module' => 'admin', 'description' => 'Manage system users']);
            $permissions[] = Permission::create(['name' => 'Manage Menus', 'slug' => 'admin.menus', 'module' => 'admin', 'description' => 'Manage system menus']);

            // Assign permissions to Super Admin (all permissions)
            $superadmin->permissions()->attach(Permission::all()->pluck('id'));

            // Assign permissions to SNK
            $snkPermissions = Permission::whereIn('slug', [
                // Jemaat - view and update (draft only, enforced in business logic)
                'jemaat.view',
                'jemaat.update',
                'jemaat.export',

                // Simpatisan - view and update (draft only)
                'simpatisan.view',
                'simpatisan.update',

                // Kartu Jemaat - view only
                'kartu_jemaat.view',
                'kartu_jemaat.print',

                // Reports
                'reports.view',
                'grafik.view',
                'rekap.view',
            ])->pluck('id');

            $snk->permissions()->attach($snkPermissions);

            // Assign permissions to Lingkungan Admin
            $lingkunganAdminPermissions = Permission::whereIn('slug', [
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
            ])->pluck('id');

            $lingkunganAdmin->permissions()->attach($lingkunganAdminPermissions);

            DB::commit();

            $this->command->info('RBAC seeding completed successfully!');
            $this->command->info('Created ' . Role::count() . ' roles');
            $this->command->info('Created ' . Permission::count() . ' permissions');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding RBAC data: ' . $e->getMessage());
            throw $e;
        }
    }
}
