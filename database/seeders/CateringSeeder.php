<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catering;
use App\Models\Venue;

class CateringSeeder extends Seeder
{
    public function run(): void
    {
        $caterings = [
            [
                'nama' => 'ekasari',
                'venue' => 'all',
                'type' => 'Basic',
                'deskripsi' => 'sudah berdiri sejak 1990',
                'portofolio_link' => 'https://instagram.com/ekasari',
                'base_price' => 25000,
            ],
            [
                'nama' => 'che che',
                'venue' => 'all',
                'type' => 'Basic',
                'deskripsi' => 'sudah ada sejak 2000',
                'portofolio_link' => 'https://instagram.com/cheche',
                'base_price' => 30000,
            ],
            [
                'nama' => 'java heritage',
                'venue' => 'java heritage',
                'type' => 'Hotel',
                'deskripsi' => 'makanan terbaik',
                'portofolio_link' => 'https://instagram.com/javaheritage',
                'buffet_price' => 100000,
                'gubugan_price' => 30000,
                'dessert_price' => 20000,
            ],
            [
                'nama' => 'grand karlita',
                'venue' => 'grand karlita',
                'type' => 'Hotel',
                'deskripsi' => 'spesial indonesian',
                'portofolio_link' => 'https://instagram.com/grandkarlita',
                'buffet_price' => 95000,
                'gubugan_price' => 25000,
                'dessert_price' => 15000,
            ],
            [
                'nama' => 'luminor',
                'venue' => 'luminor',
                'type' => 'Hotel',
                'deskripsi' => 'spesial western',
                'portofolio_link' => 'https://instagram.com/luminor',
                'buffet_price' => 120000,
                'gubugan_price' => 35000,
                'dessert_price' => 22000,
            ],
            [
                'nama' => 'oemah daun',
                'venue' => 'oemah daun',
                'type' => 'Resto',
                'deskripsi' => 'resto terbaik',
                'portofolio_link' => 'https://instagram.com/oemahdaun',
                'buffet_price' => 90000,
                'gubugan_price' => 20000,
                'dessert_price' => 10000,
            ],
        ];

        foreach ($caterings as $c) {
            $catering = Catering::create([
                'nama' => $c['nama'],
                'type' => $c['type'],
                'deskripsi' => $c['deskripsi'],
                'portofolio_link' => $c['portofolio_link'],
                'buffet_price' => $c['buffet_price'] ?? null,
                'gubugan_price' => $c['gubugan_price'] ?? null,
                'dessert_price' => $c['dessert_price'] ?? null,
                'base_price' => $c['base_price'] ?? null,
                'image1' => null,
                'image2' => null,
                'image3' => null,
                'is_active' => true,
            ]);

            if ($c['venue'] !== 'all') {
                $venue = Venue::where('nama', $c['venue'])->first();

                if ($venue) {
                    $catering->venues()->attach($venue->id);
                } else {
                    echo "❌ Venue '{$c['venue']}' tidak ditemukan untuk catering '{$c['nama']}'\n";
                }
            }
        }
    }
}
