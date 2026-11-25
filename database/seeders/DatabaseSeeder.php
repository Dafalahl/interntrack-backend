<?php

namespace Database\Seeders;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Mahasiswa
        $mahasiswa = User::updateOrCreate(
            ['email' => 'mhs@untidar.ac.id'],
            [
                'name' => 'Mahasiswa Uji',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'is_verified' => true,
            ]
        );

        // Profil mahasiswa (dibutuhkan tabel applications)
        StudentProfile::updateOrCreate(
            ['user_id' => $mahasiswa->id],
            [
                'nim' => '2200000001',
                'prodi' => 'Teknologi Informasi',
                'gpa' => 3.50,
            ]
        );

        // Mitra 1
        User::updateOrCreate(
            ['email' => 'mitra1@company.com'],
            [
                'name' => 'Mitra Satu',
                'password' => Hash::make('password123'),
                'role' => 'penyedia',
                'is_verified' => true,
            ]
        );

        // Mitra 2
        User::updateOrCreate(
            ['email' => 'mitra2@company.com'],
            [
                'name' => 'Mitra Dua',
                'password' => Hash::make('password123'),
                'role' => 'penyedia',
                'is_verified' => true,
            ]
        );
    }
}