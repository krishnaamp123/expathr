<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membaca file JSON
        $json = Storage::disk('local')->exists('data/cities.json')
            ? Storage::disk('local')->get('data/cities.json')
            : null;

        if (!$json) {
            $this->command->error('File cities.json tidak ditemukan!');
            return;
        }
        $data = json_decode($json, true);

        $cities = [];
        foreach ($data as $province) {
            foreach ($province['kota'] as $city) {
                $cities[] = [
                    'city_name' => $city,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert ke tabel cities
        DB::table('cities')->insert($cities);
    }
}
