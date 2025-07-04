<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'description',
        'percentage',
        'amount',
        'start_date',
        'end_date',
        'is_active',
    ];

    // Polymorphic many-to-many relationship
    public function vendors()
    {
        return $this->morphedByMany(Vendor::class, 'discountable');
    }

    public function caterings()
    {
        return $this->morphedByMany(Catering::class, 'discountable');
    }

    public function venues()
    {
        return $this->morphedByMany(Venue::class, 'discountable');
    }
}
