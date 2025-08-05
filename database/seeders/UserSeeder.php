<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'kode_pengguna' => 'ADM001',
            'username' => 'Roni',
            'password' => Hash::make('roni123'),
            'level' => 'Admin',
        ]);

        // Create Manager User
        // User::create([
        //     'kode_pengguna' => 'MGR001',
        //     'username' => 'manager',
        //     'password' => Hash::make('manager123'),
        //     'level' => 'Manager',
        // ]);

        // // Create Regular User
        // User::create([
        //     'kode_pengguna' => 'USR001',
        //     'username' => 'user',
        //     'password' => Hash::make('user123'),
        //     'level' => 'User',
        // ]);

        // // Create additional sample users
        // User::create([
        //     'kode_pengguna' => 'USR002',
        //     'username' => 'john_doe',
        //     'password' => Hash::make('password123'),
        //     'level' => 'User',
        // ]);

        // User::create([
        //     'kode_pengguna' => 'MGR002',
        //     'username' => 'jane_manager',
        //     'password' => Hash::make('manager456'),
        //     'level' => 'Manager',
        // ]);
    }
}
