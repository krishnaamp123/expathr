<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membaca file JSON
        $json = Storage::disk('local')->exists('data/fields.json')
            ? Storage::disk('local')->get('data/fields.json')
            : null;

        if (!$json) {
            $this->command->error('File fields.json tidak ditemukan!');
            return;
        }
        $data = json_decode($json, true);

        $fields = [];
        foreach ($data as $field) {
            foreach ($field['field_work'] as $field_work) {
                $fields[] = [
                    'field_name' => $field_work,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert ke tabel fields
        DB::table('fields')->insert($fields);
    }
}
