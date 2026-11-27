<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/locations.json');
        if (! file_exists($path)) {
            return;
        }

        $airports = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        $existingNames = array_flip(DB::table('locations')->pluck('name')->all());
        $now = now();
        $batch = [];

        foreach ($airports as $airport) {
            if (isset($existingNames[$airport['name']])) {
                continue;
            }

            $existingNames[$airport['name']] = true;
            $batch[] = [
                'name' => $airport['name'],
                'country_code' => $airport['country_code'] ?? 'INT',
                'latitude' => $airport['latitude'] ?? null,
                'longitude' => $airport['longitude'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($batch, 150) as $chunk) {
            DB::table('locations')->insert($chunk);
        }
    }
}
