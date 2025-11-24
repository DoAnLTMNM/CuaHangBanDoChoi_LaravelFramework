<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $roles = [
            ['name' => 'admin',    'description' => 'Quản trị viên cao nhất'],
            ['name' => 'staff',    'description' => 'Nhân viên'],
            ['name' => 'customer', 'description' => 'Khách hàng'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
