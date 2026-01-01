<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Service;
use App\Models\Counter;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin Pusat
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true
        ]);

        // 2. Buat Akun Petugas Loket (Staff)
        User::create([
            'name' => 'Budi Staff',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_active' => true
        ]);

        User::create([
            'name' => 'Aji Staff',
            'email' => 'aji@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_active' => true
        ]);

        User::create([
            'name' => 'Emi Staff',
            'email' => 'emi@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_active' => true
        ]);

        // 3. Buat Data Dummy Layanan
        Service::create([
            'name' => 'Customer Service',
            'code' => 'CS',
            'description' => 'Layanan keluhan dan informasi'
        ]);
        
        Service::create([
            'name' => 'Teller',
            'code' => 'TL',
            'description' => 'Setor dan tarik tunai'
        ]);

        Service::create([
            'name' => 'Layanan B',
            'code' => 'LB',
            'description' => 'Layanan B'
        ]);

        // 4. Buat Data Dummy Loket
        Counter::create(['name' => 'Loket CS', 'status' => 'open']);
        Counter::create(['name' => 'Loket TL', 'status' => 'open']);
        Counter::create(['name' => 'Loket LB', 'status' => 'open']);
    }
}