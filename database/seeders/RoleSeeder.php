<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $veterine_role = Role::create(['name' => 'veterinarian', 'guard_name' => 'veterinarian']);
        $breeder_role = Role::create(['name' => 'breeder', 'guard_name' => 'breeder']);
        $admin_role = Role::create(['name' => 'admin', 'guard_name' => 'admin']);


    }
}
