<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            [
                'name' => 'Super Admin',
                'guard_name' => 'api',
            ],
            [
                'name' => 'Admin',
                'guard_name' => 'api',
            ],
            [
                'name' => 'User',
                'guard_name' => 'api',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
