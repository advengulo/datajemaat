<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class MenuPermissionsSeeder extends Seeder
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
            // Clear existing menu-permission mappings
            DB::table('menu_permissions')->truncate();

            // Define menu-permission mappings
            $mappings = [
                // Dashboard - accessible to all who can view any data
                'dashboard' => ['jemaat.view', 'simpatisan.view'],

                // Jemaat menus
                'jemaat' => ['jemaat.view'],
                'jemaat.list' => ['jemaat.view'],
                'jemaat.add' => ['jemaat.create'],

                // Simpatisan menus
                'simpatisan' => ['simpatisan.view'],
                'simpatisan.list' => ['simpatisan.view'],
                'simpatisan.add' => ['simpatisan.create'],

                // Kartu Jemaat menu
                'kartu_jemaat' => ['kartu_jemaat.view'],

                // Reports menus
                'reports' => ['reports.view', 'grafik.view', 'rekap.view'],
                'reports.data' => ['reports.view'],
                'reports.grafik' => ['grafik.view'],
                'reports.rekap' => ['rekap.view'],

                // Master Data menus
                'master' => ['master.lingkungan', 'master.pekerjaan', 'master.pendidikan'],
                'master.lingkungan' => ['master.lingkungan'],
                'master.pekerjaan' => ['master.pekerjaan'],
                'master.pendidikan' => ['master.pendidikan'],

                // Admin menus
                'admin' => ['admin.users', 'admin.roles', 'admin.permissions', 'admin.menus'],
                'admin.users' => ['admin.users'],
                'admin.roles' => ['admin.roles'],
                'admin.permissions' => ['admin.permissions'],
                'admin.menus' => ['admin.menus'],
            ];

            $totalMappings = 0;

            foreach ($mappings as $menuSlug => $permissionSlugs) {
                $menu = Menu::where('slug', $menuSlug)->first();

                if (!$menu) {
                    $this->command->warn("Menu '{$menuSlug}' not found, skipping...");
                    continue;
                }

                foreach ($permissionSlugs as $permissionSlug) {
                    $permission = Permission::where('slug', $permissionSlug)->first();

                    if (!$permission) {
                        $this->command->warn("Permission '{$permissionSlug}' not found, skipping...");
                        continue;
                    }

                    // Attach permission to menu (only if not already attached)
                    if (!$menu->permissions()->where('permission_id', $permission->id)->exists()) {
                        $menu->permissions()->attach($permission->id);
                        $totalMappings++;
                    }
                }
            }

            DB::commit();

            $this->command->info('✓ Menu-Permission mappings seeded successfully: ' . $totalMappings . ' mappings created');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('✗ Error seeding menu-permission mappings: ' . $e->getMessage());
            throw $e;
        }
    }
}
