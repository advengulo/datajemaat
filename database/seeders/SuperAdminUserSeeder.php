<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadminRole = Role::where('slug', 'superadmin')->first();

        if (!$superadminRole) {
            $this->command->error('Superadmin role not found. Run RolesSeeder first.');
            return;
        }

        $user = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Change this on first login!
            ]
        );

        $user->roles()->syncWithoutDetaching([$superadminRole->id]);

        $this->command->info('Superadmin user created: admin / password');
        $this->command->warn('IMPORTANT: Change the default password on first login!');
    }
}
