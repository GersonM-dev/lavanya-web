<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function scopeActive(Builder $q): Builder
    {
        $today = now();
        return $q->where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $today);
            });
    }

    /**
     * Keep a discount iff *every* non‑empty requirement (vendors, caterings,
     * venues) is matched by the provided selections.
     *
     * @param  int[]|null  $vendorIds
     * @param  int|null    $cateringId
     * @param  int|null    $venueId
     */
    public function scopeMatchesSelection(
        Builder $q,
        ?array $vendorIds = null,
        ?int $cateringId = null,
        ?int $venueId = null,
    ): Builder {
        /* ---- vendor requirement ---- */
        $q->where(function ($sub) use ($vendorIds) {
            // global discount (no vendor requirement)
            $sub->whereDoesntHave('vendors');

            // OR at least one of the chosen vendors
            if (!empty($vendorIds)) {
                $sub->orWhereHas('vendors', fn($v)
                    => $v->whereIn('vendors.id', $vendorIds));
            }
        });

        /* ---- catering requirement ---- */
        $q->where(function ($sub) use ($cateringId) {
            $sub->whereDoesntHave('caterings');
            if ($cateringId) {
                $sub->orWhereHas(
                    'caterings',
                    fn($c) => $c->where('caterings.id', $cateringId)
                );
            }
        });

        /* ---- venue requirement ---- */
        $q->where(function ($sub) use ($venueId) {
            $sub->whereDoesntHave('venues');
            if ($venueId) {
                $sub->orWhereHas(
                    'venues',
                    fn($v) => $v->where('venues.id', $venueId)
                );
            }
        });

        return $q;
    }

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
