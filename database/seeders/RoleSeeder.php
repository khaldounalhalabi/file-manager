<?php

namespace Database\Seeders;

use App\Enums\RolesPermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        $roles = RolesPermissionEnum::ALL;

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['role']], []);
        }
    }
}
