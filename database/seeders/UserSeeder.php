<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'owner']);
        Role::create(['name' => 'kasir']);

        User::create([
            'username' => 'mhdhsbyy',
            'first_name' => 'Muhammad Hasby',
            'last_name' => 'Ash Shiddiqie',
            'email' => 'mhdhsbyy@gmail.com',
            'phone' => '085294644147',
            'password' => Hash::make('password'),
            'status' => 'active',
        ])->assignRole('kasir');

        User::create([
            'username' => 'daniel',
            'first_name' => 'Daniel',
            'last_name' => 'Ernawan',
            'email' => 'daniel@gmail.com',
            'phone' => '085294644144',
            'password' => Hash::make('password'),
            'status' => 'active',
        ])->assignRole('owner');
    }
}
