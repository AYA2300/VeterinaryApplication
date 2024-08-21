<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
          //
          $breeder=Admin::create([
            'name' => 'admin',
            'email'=>'admin@gmail.com',
            'password'=>Hash::make('11111111'),
            'role'=>'admin'
         ]);
         $breeder->assignRole(Role::where('name', 'admin')->first());

    }
}
