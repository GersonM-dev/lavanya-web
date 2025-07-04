<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Venue;
use App\Models\VendorCategories;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            [
                'nama' => 'miracle',
                'kategori' => 'photographer',
                'harga' => 5000000,
                'deskripsi' => 'foto',
                'is_all_venue' => true,
            ],
            [
                'nama' => 'edo',
                'kategori' => 'mc',
                'harga' => 2000000,
                'deskripsi' => 'mc',
                'is_all_venue' => true,
            ],
            [
                'nama' => 'nula',
                'kategori' => 'band', // kategori ini harus ada di VendorCategories
                'harga' => 5000000,
                'deskripsi' => 'entertainment',
                'is_all_venue' => false, // akan di-relasikan dengan venue tertentu
            ],
            [
                'nama' => 'windusujati',
                'kategori' => 'MUA',
                'harga' => 6500000,
                'deskripsi' => 'makeup',
                'is_all_venue' => true,
            ],
            [
                'nama' => 'main video',
                'kategori' => 'videographer',
                'harga' => 4000000,
                'deskripsi' => 'video',
                'is_all_venue' => true,
            ],
            [
                'nama' => 'lavanya',
                'kategori' => 'wedding organizer',
                'harga' => 6000000,
                'deskripsi' => 'wo',
                'is_all_venue' => false,
            ],
        ];

        foreach ($vendors as $v) {
            // Cari ID kategori vendor
            $category = VendorCategories::where('name', $v['kategori'])->first();

            // Buat vendor
            $vendor = Vendor::create([
                'nama' => $v['nama'],
                'vendor_category_id' => $category?->id,
                'harga' => $v['harga'],
                'deskripsi' => $v['deskripsi'],
                'portofolio_link' => null,
                'image1' => null,
                'image2' => null,
                'image3' => null,
                'is_active' => true,
                'is_mandatory' => false,
                'is_all_venue' => $v['is_all_venue'],
            ]);

            // Jika tidak untuk semua venue, tambahkan relasi pivot acak
            if (!$v['is_all_venue']) {
                $venueIds = Venue::inRandomOrder()->limit(2)->pluck('id');
                $vendor->venues()->attach($venueIds);
            }
        }
    }
}
