<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catering extends Model
{

    protected $fillable = [
        'nama',
        'type',
        'deskripsi',
        'buffet_price',
        'gubugan_price',
        'dessert_price',
        'base_price',
        'portofolio_link',
        'image1',
        'image2',
        'image3',
        'is_active',
    ];
    public function venues()
    {
        return $this->belongsToMany(Venue::class, 'catering_venue');
    }

    public function discounts()
    {
        return $this->morphToMany(Discount::class, 'discountable');
    }

}
