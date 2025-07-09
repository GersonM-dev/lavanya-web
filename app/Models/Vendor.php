<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{

    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'portofolio_link',
        'image1',
        'image2',
        'image3',
        'is_active',
        'is_mandatory',
        'is_all_venue',
        'vendor_category_id',
    ];
    public function venues()
    {
        return $this->belongsToMany(Venue::class, 'vendor_venue');
    }

    public function vendorCategory()
    {
        return $this->belongsTo(VendorCategories::class);
    }

    public function discounts()
    {
        return $this->morphToMany(Discount::class, 'discountable');
    }

    public function referrals()
    {
        return $this->belongsToMany(Referral::class);
    }

}
