<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
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
            // Clear existing roles and related data
            DB::table('role_permissions')->delete();
            DB::table('user_roles')->delete();
            DB::table('roles')->delete();

            // Create Super Admin role
            Role::create([
                'name' => 'Super Admin',
                'slug' => 'superadmin',
                'description' => 'Full access with approval rights',
                'scope' => 'global',
            ]);

            // Create SNK role
            Role::create([
                'name' => 'SNK',
                'slug' => 'snk',
                'description' => 'View and edit draft data only',
                'scope' => 'global',
            ]);

            // Create Lingkungan Admin role
            Role::create([
                'name' => 'Lingkungan Admin',
                'slug' => 'lingkungan_admin',
                'description' => 'Scoped to assigned lingkungan',
                'scope' => 'lingkungan',
            ]);

            DB::commit();

            $this->command->info('✓ Roles seeded successfully: ' . Role::count() . ' roles created');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('✗ Error seeding roles: ' . $e->getMessage());
            throw $e;
        }
    }
}
