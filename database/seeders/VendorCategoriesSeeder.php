<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VendorCategories;

class VendorCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'venue',
            'photographer',
            'decoration',
            'mc',
            'entertainment',
            'videographer',
            'makeup',
            'attire',
            'band',
            'wedding organizer',
            'catering',
            'sound system',
            'MUA',
        ];

        foreach ($categories as $name) {
            VendorCategories::create([
                'name' => $name,
            ]);
        }
    }
}
