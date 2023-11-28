<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class
        ]);

        $user = User::create([
            'first_name' => 'John Cyrus',
            'last_name' => 'Patungan',
            'email' => 'cypatungan@gmail.com',
            'password' => bcrypt('user')
        ]);

        $user->assignRole(['Super Admin']);
    }
}
