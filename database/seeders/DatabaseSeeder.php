<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Suntik Data Akun Pengguna (Users)
        DB::table('users')->insert([
            [
                'name' => 'Admin Utama',
                'email' => 'admin@cargotrace.com',
                'password' => Hash::make('password123'), // Password disamakan semua biar gampang
                'role' => 'admin',
            ],
            [
                'name' => 'Operator Logistik',
                'email' => 'operator@cargotrace.com',
                'password' => Hash::make('password123'),
                'role' => 'operator',
            ],
            [
                'name' => 'Dinda Customer', // Ini akun pelanggan untuk ngetes resi album K-Pop nanti
                'email' => 'dinda@customer.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        ]);

        // 2. Suntik Data Ekspedisi (Carriers)
        DB::table('carriers')->insert([
            ['name' => 'Korean Air Cargo', 'code' => 'KAC'],
            ['name' => 'Garuda Indonesia Cargo', 'code' => 'GIA'],
        ]);

        // 3. Suntik Data Lokasi Utama (Locations)
        DB::table('locations')->insert([
            [
                'name' => 'Incheon International Airport', 
                'country_code' => 'KR', 
                'latitude' => 37.4602, 
                'longitude' => 126.4407
            ],
            [
                'name' => 'Soekarno-Hatta International Airport', 
                'country_code' => 'ID', 
                'latitude' => -6.1256, 
                'longitude' => 106.6558
            ],
        ]);
    }
}