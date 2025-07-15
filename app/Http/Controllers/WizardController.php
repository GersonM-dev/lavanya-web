<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Vendor;
use App\Models\Catering;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Referral;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\VendorCategories;

class WizardController extends Controller
{
    public function index(Referral $referral = null)
    {
        return view('wizard.index', ['referral' => $referral]);
    }
    // 1. Get venue types (for first step)
    public function venueTypes()
    {
        return response()->json([
            'types' => [
                ['name' => 'Indoor', 'image' => asset('images/venue-type-indoor.jpg')],
                ['name' => 'Outdoor', 'image' => asset('images/venue-type-outdoor.jpg')],
                ['name' => 'Semi-Outdoor', 'image' => asset('images/venue-type-semioutdoor.png')],
            ]
        ]);
    }

    // 2. Get venues by type
    public function venues(Request $request)
    {
        $type = $request->query('type');
        $guestCount = $request->query('guest_count');
        $referralCode = $request->query('referral_id'); // <-- dari frontend

        $venuesQuery = Venue::where('type', $type)
            ->where('is_active', 1);

        if ($guestCount) {
            $venuesQuery->where('capacity', '>=', $guestCount);
        }

        // Convert referral_code ke id sebelum query
        $referralId = null;
        if ($referralCode) {
            $referralId = \App\Models\Referral::where('referral_code', $referralCode)->value('id');
        }

        if ($referralId) {
            $venuesQuery->whereHas('referrals', function ($q) use ($referralId) {
                $q->where('referrals.id', $referralId);
            });
        } elseif ($referralCode) {
            // Ada referral_code tapi tidak valid, hasil dikosongkan
            $venuesQuery->whereRaw('0=1');
        }

        $venues = $venuesQuery->get()->map(function ($venue) {
            return [
                'id' => $venue->id,
                'name' => $venue->nama,
                'image' => $venue->image_url,
                'description' => $venue->deskripsi,
                'price' => $venue->harga,
                'location' => $venue->location ?? '',
            ];
        });

        return response()->json(['venues' => $venues]);
    }

    // 3. Get vendor categories with available vendors for this venue
    public function vendorCategories(Request $request)
    {
        $venueId = $request->query('venue_id');
        $referralCode = $request->query('referral_code');

        $categories = VendorCategories::whereHas('vendors', function ($q) use ($venueId, $referralCode) {
            $q->where('is_active', 1)
                ->where(function ($q2) use ($venueId) {
                    $q2->whereHas('venues', fn($q3) => $q3->where('venues.id', $venueId))
                        ->orWhere('is_all_venue', true);
                });

            if ($referralCode) {
                $q->whereHas('referrals', fn($qr) => $qr->where('referrals.referral_code', $referralCode));
            }
        })->get()->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
            ];
        });

        return response()->json(['categories' => $categories]);
    }

    public function vendors(Request $request)
    {
        $categoryId = $request->query('category_id');
        $venueId = $request->query('venue_id');
        $referralCode = $request->query('referral_code');

        $referralId = null;
        if ($referralCode) {
            $referralId = \App\Models\Referral::where('referral_code', $referralCode)->value('id');
        }

        $vendorsQuery = Vendor::where('vendor_category_id', $categoryId)
            ->where('is_active', 1)
            ->where(function ($q) use ($venueId) {
                $q->whereHas('venues', fn($qq) => $qq->where('venues.id', $venueId))
                    ->orWhere('is_all_venue', true);
            });

        if ($referralId) {
            $vendorsQuery->whereHas('referrals', fn($q) => $q->where('referrals.id', $referralId));
        } elseif ($referralCode) {
            // Ada kode tapi tidak valid, kosongkan hasil
            $vendorsQuery->whereRaw('0=1');
        }

        $vendors = $vendorsQuery->get()->map(function ($v) {
            return [
                'id' => $v->id,
                'name' => $v->nama,
                'image' => $v->image1 ? asset('storage/' . $v->image1) : asset('images/default-vendor.jpg'),
                'price' => $v->harga,
                'description' => $v->deskripsi,
                'is_mandatory' => (bool) $v->is_mandatory,
            ];
        });

        return response()->json(['vendors' => $vendors]);
    }

    // CATERINGS
    public function caterings(Request $request)
    {
        $venueId = $request->query('venue_id');
        $referralCode = $request->query('referral_code');

        $referralId = null;
        if ($referralCode) {
            $referralId = \App\Models\Referral::where('referral_code', $referralCode)->value('id');
        }

        $cateringsQuery = Catering::where('is_active', 1)
            ->where(function ($q) use ($venueId) {
                $q->whereHas('venues', fn($qq) => $qq->where('venues.id', $venueId))
                    ->orWhere('is_all_venue', true);
            });

        if ($referralId) {
            $cateringsQuery->whereHas('referrals', fn($q) => $q->where('referrals.id', $referralId));
        } elseif ($referralCode) {
            $cateringsQuery->whereRaw('0=1');
        }

        $caterings = $cateringsQuery->get()->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->nama,
                'image' => $c->image1 ? asset('storage/' . $c->image1) : asset('images/default-catering.jpg'),
                'type' => $c->type,
                'buffet_price' => $c->buffet_price,
                'gubugan_price' => $c->gubugan_price,
                'dessert_price' => $c->dessert_price,
                'base_price' => $c->base_price,
            ];
        });

        return response()->json(['caterings' => $caterings]);
    }
    // 6. Get eligible discounts
    public function discounts(Request $request)
    {
        $venueId = $request->query('venue_id');
        $cateringId = $request->query('catering_id');
        $vendorIds = $request->query('vendor_ids', []);

        // Get all active discounts related to any selection
        $discounts = Discount::where('is_active', 1)
            ->where(function ($q) use ($venueId, $cateringId, $vendorIds) {
                $q->when(
                    $venueId,
                    fn($q2) =>
                    $q2->orWhereHas('venues', fn($qq) => $qq->where('venues.id', $venueId))
                );
                $q->when(
                    $cateringId,
                    fn($q2) =>
                    $q2->orWhereHas('caterings', fn($qq) => $qq->where('caterings.id', $cateringId))
                );
                $q->when(
                    count($vendorIds),
                    fn($q2) =>
                    $q2->orWhereHas('vendors', fn($qq) => $qq->whereIn('vendors.id', $vendorIds))
                );
            })
            ->get();

        // Now filter discounts: only keep those where all non-empty requirements are fulfilled
        $filtered = $discounts->filter(function ($discount) use ($venueId, $cateringId, $vendorIds) {
            // For each relationship, if discount requires it, must match
            if ($discount->venues()->count() && !$discount->venues()->where('venues.id', $venueId)->exists())
                return false;
            if ($discount->caterings()->count() && !$discount->caterings()->where('caterings.id', $cateringId)->exists())
                return false;
            if ($discount->vendors()->count() && !$discount->vendors()->whereIn('vendors.id', $vendorIds)->exists())
                return false;
            return true;
        })->values();

        return response()->json([
            'discounts' => $filtered->map(fn($d) => [
                'id' => $d->id,
                'name' => $d->name,
                'description' => $d->description,
                'percentage' => $d->percentage,
                'amount' => $d->amount,
            ])
        ]);
    }
    public function storeTransaction(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'customer.grooms_name' => 'required|string|max:100',
            'customer.brides_name' => 'required|string|max:100',
            'customer.guest_count' => 'required|integer|min:1',
            'customer.wedding_date' => 'required|date|after_or_equal:today',
            'customer.phone_number' => 'required|string|max:20',
            'customer.refferal_code' => 'nullable|string|max:50',
            'venue_id' => 'required|exists:venues,id',
            'catering_id' => 'required|exists:caterings,id',
            'vendors' => 'required|array|min:1',
            'vendors.*.id' => 'required|exists:vendors,id',
            'discount_ids' => 'nullable|array',
            'discount_ids.*' => 'exists:discounts,id',
        ]);

        // 2. Create customer
        $customer = Customer::create($request->input('customer'));

        // 3. Get Venue, Catering, Vendors, Discounts
        $venue = Venue::findOrFail($request->venue_id);
        $catering = Catering::findOrFail($request->catering_id);
        $vendors = Vendor::whereIn('id', collect($request->vendors)->pluck('id'))->get();
        $discounts = Discount::whereIn('id', $request->input('discount_ids', []))->get();
        $guestCount = $request->input('customer.guest_count');

        // 4. Price Calculation
        $venuePrice = $venue->harga;
        $totalBuffet = $totalGubugan = $totalDessert = $cateringTotal = 0;

        if ($catering->type === 'Hotel') {
            $buffet = $catering->buffet_price ?? 0;
            $gubugan = $catering->gubugan_price ?? 0;
            $dessert = $catering->dessert_price ?? 0;
            $totalFood = $guestCount * 3;
            $totalBuffet = $buffet * ($guestCount * 0.5);
            $totalGubugan = $gubugan * ($totalFood - ($guestCount * 0.5));
            $totalDessert = $dessert * ($guestCount * 0.5);
            $cateringTotal = $totalBuffet + $totalGubugan + $totalDessert;
        } elseif ($catering->type === 'Resto') {
            $buffet = $catering->buffet_price ?? 0;
            $gubugan = $catering->gubugan_price ?? 0;
            $dessert = $catering->dessert_price ?? 0;
            $totalBuffet = $buffet * $guestCount;
            $totalGubugan = $gubugan * $guestCount;
            $totalDessert = $dessert * ($guestCount * 0.5);
            $cateringTotal = $totalBuffet + $totalGubugan + $totalDessert;
        } elseif ($catering->type === 'Basic') {
            $cateringTotal = $catering->base_price * $guestCount;
        }

        $vendorTotal = $vendors->sum('harga');
        $totalBeforeDiscount = $venuePrice + $cateringTotal + $vendorTotal;

        // Calculate discount
        $totalDiscount = 0;
        foreach ($discounts as $discount) {
            if ($discount->amount) {
                $totalDiscount += $discount->amount;
            } elseif ($discount->percentage) {
                $totalDiscount += $totalBeforeDiscount * ($discount->percentage / 100);
            }
        }

        $finalTotal = max($totalBeforeDiscount - $totalDiscount, 0);

        // 5. Create Transaction
        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'venue_id' => $venue->id,
            'catering_id' => $catering->id,
            'transaction_date' => now(),
            'total_estimated_price' => $finalTotal,
            'status' => $request->input('status', 'pending'),
            'notes' => $request->input('notes'),
            'catering_total_price' => $cateringTotal,
            'total_buffet_price' => $totalBuffet,
            'total_gubugan_price' => $totalGubugan,
            'total_dessert_price' => $totalDessert,
        ]);

        $groomsSlug = \Str::slug($customer->grooms_name);
        $weddingDate = date('Ymd', strtotime($customer->wedding_date));
        $recapPath = $groomsSlug . '-' . $weddingDate;

        $i = 1;
        $uniqueRecapPath = $recapPath;
        while (Transaction::where('recap_link', $uniqueRecapPath)->exists()) {
            $uniqueRecapPath = $recapPath . '-' . $i++;
        }
        $transaction->recap_link = $uniqueRecapPath;
        $transaction->save();

        // 6. Attach vendors to transaction
        foreach ($request->input('vendors', []) as $vendor) {
            $transaction->vendors()->create([
                'vendor_id' => $vendor['id'],
                'estimated_price' => $vendor['estimated_price'] ?? 0,
                'is_mandatory' => $vendor['is_mandatory'] ?? false,
                'notes' => $vendor['notes'] ?? null,
            ]);
        }

        // 7. Attach discounts
        $transaction->discounts()->sync($request->input('discount_ids', []));

        // 8. Response
        return response()->json([
            'success' => true,
            'transaction_id' => $transaction->id,
            'recap_link' => $transaction->recap_link,  // Return the new recap link
            'total' => $finalTotal,
            'message' => 'Wedding transaction created!'
        ]);
    }
    public function downloadRecapPdf(\App\Models\Transaction $transaction)
    {
        $transaction->load(['customer', 'venue', 'vendorCatering', 'vendors.vendor']);
        $total = $transaction->total_estimated_price ?? 0;
        return Pdf::loadView('wizard.recap_pdf', [
            'customer' => $transaction->customer,
            'venue' => $transaction->venue,
            'catering' => $transaction->vendorCatering,
            'vendors' => $transaction->vendors,
            'total' => $total
        ])->download('wedding_recap_' . $transaction->id . '.pdf');
    }



    public function showRecap($recap_link)
    {
        $transaction = \App\Models\Transaction::with([
            'customer',
            'venue',
            'vendorCatering',
            'vendors.vendor'
        ])->where('recap_link', $recap_link)->firstOrFail();

        return view('wizard.recap', [
            'transaction' => $transaction, // <--- make sure to pass it!
            'customer' => $transaction->customer,
            'venue' => $transaction->venue,
            'catering' => $transaction->vendorCatering,
            'vendors' => $transaction->vendors,
            'total' => $transaction->total_estimated_price ?? 0,
        ]);
    }


}
