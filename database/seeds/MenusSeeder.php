<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenusSeeder extends Seeder
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
            // Clear existing menus
            Menu::truncate();

            // Create Dashboard (root level)
            $dashboard = Menu::create([
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'icon' => 'fa-dashboard',
                'route' => 'dashboard',
                'parent_id' => null,
                'order' => 1,
                'is_active' => true,
            ]);

            // Create Jemaat parent menu
            $jemaatParent = Menu::create([
                'name' => 'Data Jemaat',
                'slug' => 'jemaat',
                'icon' => 'fa-users',
                'route' => null,
                'parent_id' => null,
                'order' => 2,
                'is_active' => true,
            ]);

            // Jemaat submenus
            Menu::create([
                'name' => 'Daftar Jemaat',
                'slug' => 'jemaat.list',
                'icon' => 'fa-list',
                'route' => 'jemaat.index',
                'parent_id' => $jemaatParent->id,
                'order' => 1,
                'is_active' => true,
            ]);

            Menu::create([
                'name' => 'Tambah Jemaat',
                'slug' => 'jemaat.add',
                'icon' => 'fa-plus',
                'route' => 'jemaat.create',
                'parent_id' => $jemaatParent->id,
                'order' => 2,
                'is_active' => true,
            ]);

            // Create Simpatisan parent menu
            $simpatisanParent = Menu::create([
                'name' => 'Data Simpatisan',
                'slug' => 'simpatisan',
                'icon' => 'fa-user-plus',
                'route' => null,
                'parent_id' => null,
                'order' => 3,
                'is_active' => true,
            ]);

            // Simpatisan submenus
            Menu::create([
                'name' => 'Daftar Simpatisan',
                'slug' => 'simpatisan.list',
                'icon' => 'fa-list',
                'route' => 'simpatisan.index',
                'parent_id' => $simpatisanParent->id,
                'order' => 1,
                'is_active' => true,
            ]);

            Menu::create([
                'name' => 'Tambah Simpatisan',
                'slug' => 'simpatisan.add',
                'icon' => 'fa-plus',
                'route' => 'simpatisan.create',
                'parent_id' => $simpatisanParent->id,
                'order' => 2,
                'is_active' => true,
            ]);

            // Create Kartu Jemaat menu
            Menu::create([
                'name' => 'Kartu Jemaat',
                'slug' => 'kartu_jemaat',
                'icon' => 'fa-id-card',
                'route' => 'kartu_jemaat.index',
                'parent_id' => null,
                'order' => 4,
                'is_active' => true,
            ]);

            // Create Reports parent menu
            $reportsParent = Menu::create([
                'name' => 'Laporan',
                'slug' => 'reports',
                'icon' => 'fa-file-text',
                'route' => null,
                'parent_id' => null,
                'order' => 5,
                'is_active' => true,
            ]);

            // Reports submenus
            Menu::create([
                'name' => 'Laporan Data',
                'slug' => 'reports.data',
                'icon' => 'fa-table',
                'route' => 'reports.index',
                'parent_id' => $reportsParent->id,
                'order' => 1,
                'is_active' => true,
            ]);

            Menu::create([
                'name' => 'Grafik Statistik',
                'slug' => 'reports.grafik',
                'icon' => 'fa-bar-chart',
                'route' => 'grafik.index',
                'parent_id' => $reportsParent->id,
                'order' => 2,
                'is_active' => true,
            ]);

            Menu::create([
                'name' => 'Rekap Data',
                'slug' => 'reports.rekap',
                'icon' => 'fa-file-pdf-o',
                'route' => 'rekap.index',
                'parent_id' => $reportsParent->id,
                'order' => 3,
                'is_active' => true,
            ]);

            // Create Master Data parent menu
            $masterParent = Menu::create([
                'name' => 'Master Data',
                'slug' => 'master',
                'icon' => 'fa-database',
                'route' => null,
                'parent_id' => null,
                'order' => 6,
                'is_active' => true,
            ]);

            // Master Data submenus
            Menu::create([
                'name' => 'Lingkungan',
                'slug' => 'master.lingkungan',
                'icon' => 'fa-map-marker',
                'route' => 'master.lingkungan.index',
                'parent_id' => $masterParent->id,
                'order' => 1,
                'is_active' => true,
            ]);

            Menu::create([
                'name' => 'Pekerjaan',
                'slug' => 'master.pekerjaan',
                'icon' => 'fa-briefcase',
                'route' => 'master.pekerjaan.index',
                'parent_id' => $masterParent->id,
                'order' => 2,
                'is_active' => true,
            ]);

            Menu::create([
                'name' => 'Pendidikan',
                'slug' => 'master.pendidikan',
                'icon' => 'fa-graduation-cap',
                'route' => 'master.pendidikan.index',
                'parent_id' => $masterParent->id,
                'order' => 3,
                'is_active' => true,
            ]);

            // Create Admin parent menu
            $adminParent = Menu::create([
                'name' => 'Administrasi',
                'slug' => 'admin',
                'icon' => 'fa-cog',
                'route' => null,
                'parent_id' => null,
                'order' => 7,
                'is_active' => true,
            ]);

            // Admin submenus
            Menu::create([
                'name' => 'Kelola Pengguna',
                'slug' => 'admin.users',
                'icon' => 'fa-users',
                'route' => 'admin.users.index',
                'parent_id' => $adminParent->id,
                'order' => 1,
                'is_active' => true,
            ]);

            Menu::create([
                'name' => 'Kelola Peran',
                'slug' => 'admin.roles',
                'icon' => 'fa-shield',
                'route' => 'admin.roles.index',
                'parent_id' => $adminParent->id,
                'order' => 2,
                'is_active' => true,
            ]);

            Menu::create([
                'name' => 'Kelola Hak Akses',
                'slug' => 'admin.permissions',
                'icon' => 'fa-key',
                'route' => 'admin.permissions.index',
                'parent_id' => $adminParent->id,
                'order' => 3,
                'is_active' => true,
            ]);

            Menu::create([
                'name' => 'Kelola Menu',
                'slug' => 'admin.menus',
                'icon' => 'fa-bars',
                'route' => 'admin.menus.index',
                'parent_id' => $adminParent->id,
                'order' => 4,
                'is_active' => true,
            ]);

            DB::commit();

            $this->command->info('✓ Menus seeded successfully: ' . Menu::count() . ' menus created');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('✗ Error seeding menus: ' . $e->getMessage());
            throw $e;
        }
    }
}
