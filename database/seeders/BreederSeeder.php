<?php

namespace Database\Seeders;

use App\Models\Breeder;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BreederSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $breeder=Breeder::create([
           'name' => 'breeder1',
           'phone_number'=>'0998744322',
           'password'=>Hash::make('12345678'),
           'role'=>'breeder'
        ]);
        $breeder->assignRole(Role::where('name', 'breeder')->first());

    }
}
