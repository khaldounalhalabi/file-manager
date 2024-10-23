<?php

namespace Database\Seeders;

use App\Enums\RolesPermissionEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'admin@files.com',
            'password' => '123456789',
            'first_name' => 'Admin',
            'last_name' => 'File',
            'email_verified_at' => now()
        ])->assignRole(RolesPermissionEnum::ADMIN['role']);
    }
}
