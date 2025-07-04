<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'customer_id',
        'catering_id',
        'venue_id',
        'transaction_date',
        'total_estimated_price',
        'status',
        'notes',
        'catering_total_price',
        'total_buffet_price',
        'total_gubugan_price',
        'total_dessert_price',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function vendors()
    {
        return $this->hasMany(VendorRecord::class, 'transaction_id');
    }

    public function vendorCatering()
    {
        return $this->belongsTo(Catering::class, 'catering_id');
    }

    public function discounts()
    {
        return $this->belongsToMany(\App\Models\Discount::class, 'discount_transaction');
    }

}
