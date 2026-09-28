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
        Role::firstOrCreate(['name' => 'owner']);
        Role::firstOrCreate(['name' => 'kasir']);

        // Hapus kasir yang tidak termasuk daftar seed saat ini.
        User::where('username', 'hasby')->delete();

        User::updateOrCreate(
            ['username' => 'mhdhsbyy'],
            [
                'first_name' => 'Muhammad Hasby',
                'last_name' => 'Ash Shiddiqie',
                'email' => 'mhdhsbyy@gmail.com',
                'phone' => '085294644147',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        )->assignRole('kasir');

        User::updateOrCreate(
            ['username' => 'zein'],
            [
                'first_name' => 'Sania',
                'last_name' => 'San Zein',
                'email' => 'zein@gmail.com',
                'phone' => '0895323758181',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        )->assignRole('kasir');

        User::updateOrCreate(
            ['username' => 'daniel'],
            [
                'first_name' => 'Daniel',
                'last_name' => 'Ernawan',
                'email' => 'daniel@gmail.com',
                'phone' => '085294644144',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        )->assignRole('owner');
    }
}
