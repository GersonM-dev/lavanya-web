<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        $venues = [
            [
                'nama' => 'java heritage',
                'type' => 'Indoor',
                'deskripsi' => 'gedung kapasitas up to 800',
                'harga' => 50000000,
                'portofolio_link' => null,
                'image1' => null,
                'image2' => null,
                'image3' => null,
                'is_active' => true,
            ],
            [
                'nama' => 'grand karlita',
                'type' => 'Indoor',
                'deskripsi' => 'gedung kapasitas upto 800',
                'harga' => 30000000,
                'portofolio_link' => null,
                'image1' => null,
                'image2' => null,
                'image3' => null,
                'is_active' => true,
            ],
            [
                'nama' => 'luminor',
                'type' => 'Indoor',
                'deskripsi' => 'gedung kapasitas upto 600',
                'harga' => 27500000,
                'portofolio_link' => null,
                'image1' => null,
                'image2' => null,
                'image3' => null,
                'is_active' => true,
            ],
            [
                'nama' => 'moetel',
                'type' => 'Indoor',
                'deskripsi' => 'hotel kapasitas upto 300',
                'harga' => 10000000,
                'portofolio_link' => null,
                'image1' => null,
                'image2' => null,
                'image3' => null,
                'is_active' => true,
            ],
            [
                'nama' => 'palawi',
                'type' => 'Outdoor',
                'deskripsi' => 'hutan outdoor kapasitas upto 1000',
                'harga' => 7000000,
                'portofolio_link' => null,
                'image1' => null,
                'image2' => null,
                'image3' => null,
                'is_active' => true,
            ],
            [
                'nama' => 'oemah daun',
                'type' => 'Semi-Outdoor',
                'deskripsi' => 'resto kapasitas upto 800',
                'harga' => 0,
                'portofolio_link' => null,
                'image1' => null,
                'image2' => null,
                'image3' => null,
                'is_active' => true,
            ],
        ];

        foreach ($venues as $venue) {
            Venue::create($venue);
        }
    }
}
