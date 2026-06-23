<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Administrator Sistem', 'email' => 'admin@klinik.com', 'role' => 'admin'],
            ['name' => 'Budi (Karyawan Gudang)', 'email' => 'karyawan@klinik.com', 'role' => 'karyawan'],
            ['name' => 'Bidan Siti Aminah', 'email' => 'bidan@klinik.com', 'role' => 'bidan'],
            ['name' => 'Dr. Tirta (Pemilik)', 'email' => 'pemilik@klinik.com', 'role' => 'pemilik'],
            ['name' => 'Portal Supplier PT Kimia', 'email' => 'supplier@klinik.com', 'role' => 'supplier'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'), // Semua kata sandi defaultnya: password
                'role' => $user['role'],
            ]);
        }
    }
}
