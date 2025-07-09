<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = ['referral_code', 'slug'];

    public function venues()
    {
        return $this->belongsToMany(Venue::class);
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class);
    }

    public function caterings()
    {
        return $this->belongsToMany(Catering::class, 'referral_catering');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function ($referral) {
            // If slug is not set, use referral_code (slugified if you wish)
            if (empty($referral->slug)) {
                $referral->slug = str($referral->referral_code)->slug('-');
            }
        });

        // Optional: keep slug in sync if referral_code is updated AND slug is still empty
        static::updating(function ($referral) {
            if (empty($referral->slug)) {
                $referral->slug = str($referral->referral_code)->slug('-');
            }
        });
    }

}
