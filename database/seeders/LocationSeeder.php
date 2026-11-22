<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            // Asia
            ['name' => 'Changi Airport (SIN) - Singapore'],
            ['name' => 'Kuala Lumpur International Airport (KUL) - Malaysia'],
            ['name' => 'Suvarnabhumi Airport (BKK) - Thailand'],
            ['name' => 'Beijing Capital International Airport (PEK) - China'],
            ['name' => 'Hong Kong International Airport (HKG) - Hong Kong'],
            
            // Timur Tengah
            ['name' => 'Dubai International Airport (DXB) - UAE'],
            ['name' => 'Hamad International Airport (DOH) - Qatar'],
            ['name' => 'King Abdulaziz International Airport (JED) - Saudi Arabia'],

            // Eropa
            ['name' => 'Heathrow Airport (LHR) - United Kingdom'],
            ['name' => 'Charles de Gaulle Airport (CDG) - France'],
            ['name' => 'Frankfurt Airport (FRA) - Germany'],
            ['name' => 'Amsterdam Airport Schiphol (AMS) - Netherlands'],

            // Amerika
            ['name' => 'John F. Kennedy International Airport (JFK) - USA'],
            ['name' => 'Los Angeles International Airport (LAX) - USA'],
            ['name' => 'Toronto Pearson International Airport (YYZ) - Canada'],
            ['name' => 'São Paulo/Guarulhos International Airport (GRU) - Brazil'],

            // Australia & Oseania
            ['name' => 'Sydney Kingsford Smith Airport (SYD) - Australia'],
            ['name' => 'Melbourne Airport (MEL) - Australia'],
            ['name' => 'Auckland Airport (AKL) - New Zealand'],
        ];

        foreach ($airports as $airport) {
            // Menambahkan 'country_code' => 'INT' sebagai nilai default agar database tidak error
            Location::firstOrCreate(
                ['name' => $airport['name']],
                ['country_code' => 'INT']
            );
        }
    }
}