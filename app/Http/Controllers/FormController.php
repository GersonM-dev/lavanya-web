<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Catering;
use App\Models\Customer;
use App\Models\Referral;
use App\Models\Transaction;
use App\Models\Discount;
use App\Models\Venue;
use App\Models\VendorCategories;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index(Referral $referral = null)
    {
        return view('form', ['referral' => $referral]);
    }

    public function getVenueTypes()
    {
        return response()->json([
            'types' => [
                ['name' => 'Indoor', 'image' => asset('images/venue-type-indoor.jpg')],
                ['name' => 'Outdoor', 'image' => asset('images/venue-type-outdoor.jpg')],
                ['name' => 'Semi-Outdoor', 'image' => asset('images/venue-type-semioutdoor.png')],
            ]
        ]);
    }

    public function getVenue(Request $request)
    {
        $query = Venue::query();

        // If referral_code is present, always restrict to that referral's venues
        if ($request->filled('referral_code')) {
            $referralCode = $request->query('referral_code');
            $query->whereHas('referrals', function ($ref) use ($referralCode) {
                $ref->where('referral_code', $referralCode);
            });
        }


        // Always apply type and guest_count if present
        if ($request->has('type')) {
            $query->where('type', $request->query('type'));
        }
        if ($request->has('guest_count')) {
            $query->where('capacity', '>=', $request->query('guest_count'));
        }

        $venues = $query->get()->map(function ($venue) {
            return [
                'id' => $venue->id,
                'name' => $venue->nama,
                'image' => $venue->image_url,
                'description' => $venue->deskripsi,
                'price' => $venue->harga,
                'portofolio_link' => $venue->portofolio_link,
            ];
        });

        return response()->json(['venues' => $venues]);
    }



    public function getCaterings(Request $request)
    {
        $query = Catering::query();

        if ($request->has('venue_id')) {
            $query->whereHas('venues', function ($v) use ($request) {
                $v->where('venue_id', $request->query('venue_id'));
            });
        }

        if ($request->has('referral_code')) {
            $referralCode = $request->query('referral_code');
            // Try filtering by referral_code first
            $referralFiltered = (clone $query)->whereHas('referrals', function ($ref) use ($referralCode) {
                $ref->where('referral_code', $referralCode);
            });
            if ($referralFiltered->count() > 0) {
                $query = $referralFiltered;
            }
            // else: do NOT apply referral_code filter
        }

        $caterings = $query->get()->map(function ($catering) {
            return [
                'id' => $catering->id,
                'name' => $catering->nama,
                'image' => $catering->image1 ? asset('storage/' . $catering->image1) : asset('images/default-catering.jpg'),
                'type' => $catering->type,
                'buffet_price' => $catering->buffet_price,
                'gubugan_price' => $catering->gubugan_price,
                'dessert_price' => $catering->dessert_price,
                'base_price' => $catering->base_price,
                'description' => $catering->deskripsi,
                'portofolio_link' => $catering->portofolio_link,
            ];
        });

        return response()->json(['caterings' => $caterings]);
    }


    public function getVendorCategories(Request $request)
    {
        $vendorCategories = VendorCategories::all()

            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
            });

        return response()->json(['categories' => $vendorCategories]);
    }

    public function getVendors(Request $request)
    {
        $categoryId = $request->query('category_id');
        $venueId = $request->query('venue_id');
        $referralCode = $request->query('referral_code');

        Log::info('getVendors API called', [
            'venue_id' => $venueId,
            'category_id' => $categoryId,
            'referral_code' => $referralCode,
        ]);

        $query = \App\Models\Vendor::query()
            ->where('is_active', 1)
            ->when($categoryId, function ($q, $categoryId) {
                $q->where('vendor_category_id', $categoryId);
            })
            ->when($venueId, function ($q, $venueId) {
                $q->where(function ($q2) use ($venueId) {
                    $q2->where('is_all_venue', 1)
                        ->orWhereHas('venues', function ($v) use ($venueId) {
                            $v->where('venues.id', $venueId);
                        });
                });
            });

        // If referral_code is set, filter first
        $filtered = $query;
        if ($referralCode) {
            $filtered = (clone $query)->whereHas('referrals', function ($ref) use ($referralCode) {
                $ref->where('referral_code', $referralCode);
            });
            // If there are vendors found for the referral code, use only those
            if ($filtered->count() > 0) {
                $query = $filtered;
            }
            // else: do NOT apply referral filter
        }

        $vendors = $query->get()->map(function ($vendor) {
            return [
                'id' => $vendor->id,
                'name' => $vendor->nama,
                'image' => $vendor->image1 ? asset('storage/' . $vendor->image1) : asset('images/default-vendor.jpg'),
                'description' => $vendor->deskripsi,
                'price' => $vendor->harga,
                'portofolio_link' => $vendor->portofolio_link,
                'is_mandatory' => !!$vendor->is_mandatory,
            ];
        });

        return response()->json(['vendors' => $vendors]);
    }


    public function getDiscounts(Request $request)
    {
        $venueId = $request->query('venue_id');
        $cateringId = $request->query('catering_id');

        $vendorIds = $request->query('vendor_ids', []);
        $vendorIds = is_array($vendorIds)
            ? array_filter($vendorIds)
            : array_filter(explode(',', $vendorIds));

        $discounts = Discount::query()
            ->active()
            ->matchesSelection($vendorIds, $cateringId, $venueId)
            ->get()
            ->map(fn($d) => [
                'id' => $d->id,
                'name' => $d->name,
                'description' => $d->description,
                'percentage' => $d->percentage,
                'amount' => $d->amount,
                'start_date' => $d->start_date,
                'end_date' => $d->end_date,
            ]);

        return response()->json(['discounts' => $discounts]);
    }

    public function postWedding(Request $request)
    {
        /* ---------- Validation ---------- */
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
            'vendors.*.estimated_price' => 'nullable|numeric|min:0',
            'vendors.*.is_mandatory' => 'boolean',
            'vendors.*.notes' => 'nullable|string',

            'discount_ids' => 'nullable|array',
            'discount_ids.*' => 'exists:discounts,id',
        ]);

        /* ---------- Extract basic data ---------- */
        $venueId = $request->venue_id;
        $cateringId = $request->catering_id;
        $vendorLines = collect($request->vendors);
        $vendorIds = $vendorLines->pluck('id')->all();
        $guestCount = $request->input('customer.guest_count');

        /* ---------- DB transaction ---------- */
        DB::beginTransaction();
        try {
            /* ---- create Customer ---- */
            $customer = Customer::create($request->input('customer'));

            /* ---- fetch core objects ---- */
            $venue = Venue::findOrFail($venueId);
            $catering = Catering::findOrFail($cateringId);
            $vendors = Vendor::whereIn('id', $vendorIds)->get();

            /* ---- pull eligible & active discounts ---- */
            $discounts = Discount::active()
                ->matchesSelection($vendorIds, null, $venueId)   // we have no catering‑specific discounts here; pass null or $cateringId if needed
                ->whereIn('id', $request->input('discount_ids', []))
                ->get();

            /* ---------- Price calculation ---------- */

            // venue
            $venuePrice = $venue->harga;

            // catering
            $cateringTotals = $this->calculateCateringCost($catering, $guestCount);
            [$cateringTotal, $totalBuffet, $totalGubugan, $totalDessert] = $cateringTotals;

            // vendors (use estimated_price if provided)
            $vendorTotal = $vendorLines->sum(function ($line) use ($vendors) {
                $price = $line['estimated_price'] ?? null;
                if (is_null($price)) {
                    $price = optional($vendors->firstWhere('id', $line['id']))->harga ?? 0;
                }
                return $price;
            });

            $totalBeforeDiscount = $venuePrice + $cateringTotal + $vendorTotal;

            // discounts
            $totalDiscount = $discounts->sum(function ($d) use ($totalBeforeDiscount) {
                return $d->amount
                    ?: $totalBeforeDiscount * ($d->percentage / 100);
            });

            $finalTotal = max($totalBeforeDiscount - $totalDiscount, 0);

            /* ---------- Create Transaction + pivots ---------- */
            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'venue_id' => $venueId,
                'catering_id' => $cateringId,
                'transaction_date' => now(),
                'total_estimated_price' => $finalTotal,
                'total_before_discount' => $totalBeforeDiscount,
                'status' => $request->input('status', 'pending'),
                'notes' => $request->input('notes'),
                'catering_total_price' => $cateringTotal,
                'total_buffet_price' => $totalBuffet,
                'total_gubugan_price' => $totalGubugan,
                'total_dessert_price' => $totalDessert,
            ]);

            // unique recap link
            $this->generateRecapLink($transaction, $customer);

            // attach vendors with extra fields
            foreach ($vendorLines as $line) {
                $transaction->vendors()->create([
                    'vendor_id' => $line['id'],
                    'estimated_price' => $line['estimated_price'] ?? 0,
                    'is_mandatory' => $line['is_mandatory'] ?? false,
                    'notes' => $line['notes'] ?? null,
                ]);
            }

            // attach discounts
            $transaction->discounts()->sync($discounts->pluck('id'));

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json(['success' => false, 'message' => 'Failed to create wedding.'], 500);
        }

        /* ---------- WhatsApp notification (non‑blocking) ---------- */
        $this->notifyViaWhatsapp($request->input('customer'), $transaction->id);

        /* ---------- Response ---------- */
        return response()->json([
            'success' => true,
            'transaction_id' => $transaction->id,
            'recap_link' => $transaction->recap_link,
            'total' => $finalTotal,
            'message' => 'Wedding transaction created!',
        ]);
    }

    /* ============================================================= */
    /* === Helper methods – keep them in the same controller or a trait */
    private function calculateCateringCost(Catering $c, int $guests): array
    {
        $buffet = $c->buffet_price ?? 0;
        $gubugan = $c->gubugan_price ?? 0;
        $dessert = $c->dessert_price ?? 0;

        switch ($c->type) {
            case 'Hotel':
                $totalFood = $guests * 3;
                $totBuffet = $buffet * ($guests * 0.5);
                $totGubugan = $gubugan * ($totalFood - ($guests * 0.5));
                $totDessert = $dessert * ($guests * 0.5);
                $cateringTotal = $totBuffet + $totGubugan + $totDessert;
                break;

            case 'Resto':
                $totBuffet = $buffet * $guests;
                $totGubugan = $gubugan * $guests;
                $totDessert = $dessert * ($guests * 0.5);
                $cateringTotal = $totBuffet + $totGubugan + $totDessert;
                break;

            default: // Basic
                $totBuffet = $totGubugan = $totDessert = 0;
                $cateringTotal = $c->base_price * $guests;
        }

        return [$cateringTotal, $totBuffet, $totGubugan, $totDessert];
    }

    private function generateRecapLink(Transaction $tx, Customer $c): void
    {
        $date = $c->wedding_date instanceof \Carbon\Carbon
            ? $c->wedding_date
            : \Carbon\Carbon::parse($c->wedding_date);

        $base = Str::slug($c->grooms_name) . '-' . $date->format('Ymd');
        $slug = $base;
        $suffix = 1;

        while (Transaction::where('recap_link', $slug)->exists()) {
            $slug = "{$base}-" . $suffix++;
        }

        $tx->update(['recap_link' => $slug]);
    }

    private function notifyViaWhatsapp(array $cust, int $txId): void
    {
        try {
            $phone = $cust['phone_number'];
            $intl = (substr($phone, 0, 1) === '0') ? '62' . substr($phone, 1) : $phone;

            $msg = "Wedding Baru!\n"
                . "Pria: {$cust['grooms_name']}\n"
                . "Wanita: {$cust['brides_name']}\n"
                . "Tanggal: {$cust['wedding_date']}\n"
                . "Tamu: {$cust['guest_count']}\n"
                . "No HP: https://wa.me/{$intl}\n"
                . "ID Transaksi: {$txId}";

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    'target' => '085156106221',
                    'message' => $msg,
                    'countryCode' => '62',
                ],
                CURLOPT_HTTPHEADER => ['Authorization: 1DE6DXXKg79mL8ivtLkK'],
            ]);
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                Log::error('Fonnte error: ' . curl_error($curl));
            }
            curl_close($curl);
            Log::info('Fonnte response: ' . $response);
        } catch (\Throwable $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }
    }
}
