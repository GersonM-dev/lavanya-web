<?php
namespace App\Livewire;

use App\Models\VendorCategories;
use Livewire\Component;
use App\Models\Venue;
use App\Models\Catering;
use App\Models\VendorCategory;

class WeddingOrderForm extends Component
{
    public $step = 1;

    // Customer Info
    public $grooms_name, $brides_name, $phone_number, $guest_count, $wedding_date;

    // Venue
    public $venue_type, $venue_id;

    // Catering
    public $catering_id;
    public $buffet_price = 0, $gubugan_price = 0, $dessert_price = 0, $base_price = 0, $catering_total_price = 0;

    // Vendor Selection
    public $vendor_step = 0;
    public $vendor_categories = [];
    public $selected_vendors = []; // ['category_id' => vendor_id]

    // --- Validation Rules ---
    protected $rules = [
        'grooms_name' => 'required|string',
        'brides_name' => 'required|string',
        'phone_number' => 'required|string',
        'guest_count' => 'required|integer|min:1',
        'wedding_date' => 'required|date',
    ];

    // Step navigation
    public function nextStep()
    {
        $this->validateStep();

        // Vendor step logic
        if ($this->step === 4 && $this->vendor_step < count($this->vendor_categories) - 1) {
            $this->vendor_step++;
            return;
        }

        $this->step++;
    }

    public function prevStep()
    {
        if ($this->step === 4 && $this->vendor_step > 0) {
            $this->vendor_step--;
            return;
        }
        $this->step--;
    }

    public function updatedCateringId()
    {
        $this->calculateCatering();
    }

    public function updatedGuestCount()
    {
        $this->calculateCatering();
    }

    public function updatedVenueId()
    {
        $this->loadVendorCategories();
        $this->selected_vendors = [];
        $this->vendor_step = 0;
    }

    private function validateStep()
    {
        if ($this->step === 1) {
            $this->validateOnly([
                'grooms_name', 'brides_name', 'phone_number', 'guest_count', 'wedding_date'
            ]);
        }
        if ($this->step === 2) {
            $this->validate(['venue_type' => 'required', 'venue_id' => 'required']);
        }
        if ($this->step === 3) {
            $this->validate(['catering_id' => 'required']);
        }
        if ($this->step === 4 && count($this->vendor_categories)) {
            // Optionally, require selection per category:
            // $currentCat = $this->vendor_categories[$this->vendor_step];
            // $this->validate(['selected_vendors.' . $currentCat['id'] => 'required']);
        }
    }

    private function calculateCatering()
    {
        $catering = Catering::find($this->catering_id);
        $guestCount = $this->guest_count ?? 0;

        $this->buffet_price = 0;
        $this->gubugan_price = 0;
        $this->dessert_price = 0;
        $this->base_price = 0;
        $this->catering_total_price = 0;

        if ($catering && $guestCount) {
            if ($catering->type === 'Hotel') {
                $totalFood = $guestCount * 3;
                $this->buffet_price = $catering->buffet_price * ($guestCount * 0.5);
                $this->gubugan_price = $catering->gubugan_price * ($totalFood - ($guestCount * 0.5));
                $this->dessert_price = $catering->dessert_price * ($guestCount * 0.5);
                $this->catering_total_price = $this->buffet_price + $this->gubugan_price + $this->dessert_price;
            } elseif ($catering->type === 'Resto') {
                $this->buffet_price = $catering->buffet_price * $guestCount;
                $this->gubugan_price = $catering->gubugan_price * $guestCount;
                $this->dessert_price = $catering->dessert_price * ($guestCount * 0.5);
                $this->catering_total_price = $this->buffet_price + $this->gubugan_price + $this->dessert_price;
            } elseif ($catering->type === 'Basic') {
                $this->base_price = $catering->base_price * $guestCount;
                $this->catering_total_price = $this->base_price;
            }
        }
    }

    private function loadVendorCategories()
    {
        $venueId = $this->venue_id;
        $this->vendor_categories = VendorCategories::whereHas('vendors', function ($q) use ($venueId) {
            $q->whereHas('venues', function ($q2) use ($venueId) {
                $q2->where('venues.id', $venueId);
            })->orWhere('is_all_venue', true);
        })
        ->with(['vendors' => function ($q) use ($venueId) {
            $q->whereHas('venues', function ($q2) use ($venueId) {
                $q2->where('venues.id', $venueId);
            })->orWhere('is_all_venue', true);
        }])
        ->get()
        ->toArray();
    }

    public function render()
    {
        $venues = $this->venue_type
            ? Venue::where('type', $this->venue_type)->get()
            : collect();
        $caterings = $this->venue_id
            ? Catering::whereHas('venues', fn($q) => $q->where('venues.id', $this->venue_id))
                ->orWhere('is_all_venue', true)->get()
            : collect();

        return view('livewire.wedding-order-form', [
            'venues' => $venues,
            'caterings' => $caterings,
            'vendor_categories' => $this->vendor_categories,
            'vendor_step' => $this->vendor_step,
            'selected_vendors' => $this->selected_vendors,
        ]);
    }
}
