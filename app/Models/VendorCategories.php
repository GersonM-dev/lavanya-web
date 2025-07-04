<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCategories extends Model
{
    protected $fillable = [
        'name',
    ];

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'vendor_category_id');
    }
}
