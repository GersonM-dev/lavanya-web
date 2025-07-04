<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorRecord extends Model
{
    protected $fillable = [
        'transaction_id',
        'vendor_id',
        'estimated_price',
        'is_mandatory',
        'notes',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
