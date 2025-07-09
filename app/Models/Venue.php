<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'nama',
        'type',
        'deskripsi',
        'harga',
        'portofolio_link',
        'image1',
        'image2',
        'image3',
        'is_active',
        'capacity', // Added capacity field
    ];

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_venue');
    }

    public function caterings()
    {
        return $this->belongsToMany(Catering::class, 'catering_venue');
    }

    public function getImageUrlAttribute()
    {
        return $this->image1
            ? asset('storage/' . $this->image1)
            : asset('images/default-venue.jpg'); // fallback default image
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
