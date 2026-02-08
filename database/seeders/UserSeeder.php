<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Akun default untuk testing:
     * - Admin: admin@example.com / password
     * - Petugas: petugas@example.com / password
     * - Siswa: siswa@example.com / password
     * - Masyarakat: masyarakat@example.com / password
     */
    public function run(): void
    {
        // Admin Account
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Petugas Account
        User::create([
            'name' => 'Petugas',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'email_verified_at' => now(),
        ]);

        // Siswa Account
        User::create([
            'name' => 'Siswa',
            'email' => 'siswa@example.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'email_verified_at' => now(),
        ]);

        // Masyarakat Account
        User::create([
            'name' => 'Masyarakat',
            'email' => 'masyarakat@example.com',
            'password' => Hash::make('password'),
            'role' => 'masyarakat',
            'email_verified_at' => now(),
        ]);
    }
}
