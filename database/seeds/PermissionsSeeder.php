<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
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
            // Clear existing permissions
            Permission::truncate();

            // Jemaat Module Permissions
            Permission::create([
                'name' => 'View Jemaat',
                'slug' => 'jemaat.view',
                'module' => 'jemaat',
                'description' => 'View church members data'
            ]);

            Permission::create([
                'name' => 'Create Jemaat',
                'slug' => 'jemaat.create',
                'module' => 'jemaat',
                'description' => 'Create new church member'
            ]);

            Permission::create([
                'name' => 'Update Jemaat',
                'slug' => 'jemaat.update',
                'module' => 'jemaat',
                'description' => 'Update church member data'
            ]);

            Permission::create([
                'name' => 'Delete Jemaat',
                'slug' => 'jemaat.delete',
                'module' => 'jemaat',
                'description' => 'Delete church member'
            ]);

            Permission::create([
                'name' => 'Approve Jemaat',
                'slug' => 'jemaat.approve',
                'module' => 'jemaat',
                'description' => 'Approve church member data changes'
            ]);

            Permission::create([
                'name' => 'Export Jemaat',
                'slug' => 'jemaat.export',
                'module' => 'jemaat',
                'description' => 'Export church member data'
            ]);

            // Simpatisan Module Permissions
            Permission::create([
                'name' => 'View Simpatisan',
                'slug' => 'simpatisan.view',
                'module' => 'simpatisan',
                'description' => 'View sympathizer data'
            ]);

            Permission::create([
                'name' => 'Create Simpatisan',
                'slug' => 'simpatisan.create',
                'module' => 'simpatisan',
                'description' => 'Create new sympathizer'
            ]);

            Permission::create([
                'name' => 'Update Simpatisan',
                'slug' => 'simpatisan.update',
                'module' => 'simpatisan',
                'description' => 'Update sympathizer data'
            ]);

            Permission::create([
                'name' => 'Delete Simpatisan',
                'slug' => 'simpatisan.delete',
                'module' => 'simpatisan',
                'description' => 'Delete sympathizer'
            ]);

            Permission::create([
                'name' => 'Approve Simpatisan',
                'slug' => 'simpatisan.approve',
                'module' => 'simpatisan',
                'description' => 'Approve sympathizer data changes'
            ]);

            // Kartu Jemaat Module Permissions
            Permission::create([
                'name' => 'View Kartu Jemaat',
                'slug' => 'kartu_jemaat.view',
                'module' => 'kartu_jemaat',
                'description' => 'View member cards'
            ]);

            Permission::create([
                'name' => 'Print Kartu Jemaat',
                'slug' => 'kartu_jemaat.print',
                'module' => 'kartu_jemaat',
                'description' => 'Print member cards'
            ]);

            // Master Module Permissions
            Permission::create([
                'name' => 'Manage Lingkungan',
                'slug' => 'master.lingkungan',
                'module' => 'master',
                'description' => 'Manage neighborhood master data'
            ]);

            Permission::create([
                'name' => 'Manage Pekerjaan',
                'slug' => 'master.pekerjaan',
                'module' => 'master',
                'description' => 'Manage job types master data'
            ]);

            Permission::create([
                'name' => 'Manage Pendidikan',
                'slug' => 'master.pendidikan',
                'module' => 'master',
                'description' => 'Manage education levels master data'
            ]);

            // Reports Module Permissions
            Permission::create([
                'name' => 'View Reports',
                'slug' => 'reports.view',
                'module' => 'reports',
                'description' => 'View various reports'
            ]);

            Permission::create([
                'name' => 'View Grafik',
                'slug' => 'grafik.view',
                'module' => 'grafik',
                'description' => 'View statistical graphs'
            ]);

            Permission::create([
                'name' => 'View Rekap',
                'slug' => 'rekap.view',
                'module' => 'rekap',
                'description' => 'View data recaps'
            ]);

            // Admin Module Permissions
            Permission::create([
                'name' => 'Manage Roles',
                'slug' => 'admin.roles',
                'module' => 'admin',
                'description' => 'Manage user roles'
            ]);

            Permission::create([
                'name' => 'Manage Permissions',
                'slug' => 'admin.permissions',
                'module' => 'admin',
                'description' => 'Manage permissions'
            ]);

            Permission::create([
                'name' => 'Manage Users',
                'slug' => 'admin.users',
                'module' => 'admin',
                'description' => 'Manage system users'
            ]);

            Permission::create([
                'name' => 'Manage Menus',
                'slug' => 'admin.menus',
                'module' => 'admin',
                'description' => 'Manage system menus'
            ]);

            DB::commit();

            $this->command->info('✓ Permissions seeded successfully: ' . Permission::count() . ' permissions created');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('✗ Error seeding permissions: ' . $e->getMessage());
            throw $e;
        }
    }
}
