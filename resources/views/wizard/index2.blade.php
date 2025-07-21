<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lavanya Wedding Planner</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Cinzel', serif;
        }
    </style>
    <style>
        body {
            background-image: url('{{ asset('images/bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        [x-cloak] {
            display: none !important;
        }

        .step-card {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            position: absolute;
            width: 100%;
        }

        .slide-in-right {
            animation: slide-in-right 0.5s ease-in-out forwards;
        }

        .slide-out-left {
            animation: slide-out-left 0.5s ease-in-out forwards;
        }

        .slide-in-left {
            animation: slide-in-left 0.5s ease-in-out forwards;
        }

        .slide-out-right {
            animation: slide-out-right 0.5s ease-in-out forwards;
        }

        @keyframes slide-in-right {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slide-out-left {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(-100%);
                opacity: 0;
            }
        }

        @keyframes slide-in-left {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slide-out-right {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
</head>

<body class="antialiased font-sans text-gray-800">

    <div x-data="wizard()" x-cloak class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-4xl relative" style="min-height: 600px;">
            <!-- Step 1: Landing -->
            <div x-show="step === 1" class="text-center step-card p-8" x-transition:enter="slide-in-right"
                x-transition:leave="slide-out-left">
                <img src="{{ asset('images/logo.jpg') }}" alt="Lavanya Logo"
                    class="mx-auto w-full max-w-xs sm:max-w-sm h-auto">
                <p class="text-lg text-gray-500 mb-2">Let's create your unforgettable day, step by step.</p>
                <button @click="nextStep()"
                    class="bg-amber-400 text-black font-semibold py-3 px-8 rounded-full hover:bg-amber-500 transition-all duration-300 shadow-lg">
                    Start Planning
                </button>
            </div>

            <!-- Step 2: Customer Form -->
            <div x-show="step === 2" class="step-card p-8" x-transition:enter="slide-in-right"
                x-transition:leave="slide-out-left">
                <h2 class="text-2xl font-bold mb-6 text-center">Tell Us About Yourselves</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="text" x-model="form.customer.grooms_name" placeholder="Groom's Full Name"
                        class="p-3 border rounded-lg w-full">
                    <input type="text" x-model="form.customer.brides_name" placeholder="Bride's Full Name"
                        class="p-3 border rounded-lg w-full">
                    <input type="number" x-model.number="form.customer.guest_count" placeholder="Number of Guests"
                        class="p-3 border rounded-lg w-full">
                    <input type="date" x-model="form.customer.wedding_date" class="p-3 border rounded-lg w-full">
                    <input type="text" x-model="form.customer.referral_code" placeholder="Referral Code (optional)"
                        class="p-3 border rounded-lg w-full" :readonly="!!initialReferralCode" />
                    <input type="tel" x-model="form.customer.phone_number" placeholder="WhatsApp Number (e.g. 0812...)"
                        class="p-3 border rounded-lg w-full">
                </div>
                <div class="flex justify-between mt-8">
                    <button @click="prevStep()"
                        class="bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-full hover:bg-gray-400 transition">Back</button>
                    <button @click="nextStep()" :disabled="!isCustomerFormValid()"
                        :class="{'opacity-50 cursor-not-allowed': !isCustomerFormValid()}"
                        class="bg-amber-400 text-black font-bold py-2 px-6 rounded-full hover:bg-amber-500 transition">Next</button>
                </div>
            </div>

            <!-- Step 3: Venue Type -->
            <div x-show="step === 3" class="step-card p-8" x-transition:enter="slide-in-right"
                x-transition:leave="slide-out-left">
                <h2 class="text-2xl font-bold mb-6 text-center">Choose Your Venue Style</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <template x-for="type in venueTypes" :key="type.name">
                        <div @click="selectVenueType(type.name)"
                            class="cursor-pointer border-4 rounded-lg overflow-hidden transition-all"
                            :class="{'border-amber-400 shadow-xl': form.venue_type === type.name, 'border-transparent hover:border-amber-200': form.venue_type !== type.name}">
                            <img :src="type.image" :alt="type.name" class="w-full h-48 object-cover">
                            <p class="text-center font-bold p-3 bg-white" x-text="type.name"></p>
                        </div>
                    </template>
                </div>
                <div class="flex justify-between mt-8">
                    <button @click="prevStep()"
                        class="bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-full hover:bg-gray-400 transition">Back</button>
                    <button @click="fetchVenues(); nextStep()" :disabled="!form.venue_type"
                        :class="{'opacity-50 cursor-not-allowed': !form.venue_type}"
                        class="bg-amber-400 text-black font-bold py-2 px-6 rounded-full hover:bg-amber-500 transition">Next</button>
                </div>
            </div>

            <!-- Step 4: Venue Selection -->
            <div x-show="step === 4" class="step-card p-8" x-transition:enter="slide-in-right"
                x-transition:leave="slide-out-left">
                <h2 class="text-2xl font-bold mb-6 text-center">Select a Venue</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[60vh] overflow-y-auto p-2">
                    <template x-if="loading.venues">
                        <p class="col-span-full text-center">Loading venues...</p>
                    </template>
                    <template x-if="!loading.venues && venues.length === 0">
                        <p class="col-span-full text-center">No venues found for your criteria.</p>
                    </template>
                    <template x-for="venue in venues" :key="venue.id">
                        <div @click="selectVenue(venue)"
                            class="cursor-pointer border-4 rounded-lg overflow-hidden transition-all"
                            :class="{'border-amber-400 shadow-xl': form.venue_id === venue.id, 'border-transparent hover:border-amber-200': form.venue_id !== venue.id}">
                            <img :src="venue.image" :alt="venue.name" class="w-full h-40 object-cover">
                            <div class="p-4 bg-white">
                                <h3 class="font-bold text-center" x-text="venue.name"></h3>
                                <p class="text-sm text-gray-600" x-text="venue.location"></p>
                                <div class="flex justify-center mt-4">
                                    <button @click.stop="openModal(venue)"
                                        class="bg-amber-400 text-sm text-black font-bold py-1 px-3 rounded-full hover:bg-amber-500 transition">
                                        Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="flex justify-between mt-8">
                    <button @click="prevStep()"
                        class="bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-full hover:bg-gray-400 transition">Back</button>
                    <button @click="fetchCaterings(); nextStep()" :disabled="!form.venue_id"
                        :class="{'opacity-50 cursor-not-allowed': !form.venue_id}"
                        class="bg-amber-400 text-black font-bold py-2 px-6 rounded-full hover:bg-amber-500 transition">Next</button>
                </div>
            </div>

            <!-- Step 5: Catering Selection -->
            <div x-show="step === 5" class="step-card p-8" x-transition:enter="slide-in-right"
                x-transition:leave="slide-out-left">
                <h2 class="text-2xl font-bold mb-6 text-center">Choose Your Catering</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[60vh] overflow-y-auto p-2">
                    <template x-if="loading.caterings">
                        <p class="col-span-full text-center">Loading caterings...</p>
                    </template>
                    <template x-if="!loading.caterings && caterings.length === 0">
                        <p class="col-span-full text-center">No caterings found for this venue.</p>
                    </template>
                    <template x-for="catering in caterings" :key="catering.id">
                        <div @click="selectCatering(catering)"
                            class="cursor-pointer border-4 rounded-lg overflow-hidden transition-all"
                            :class="{'border-amber-400 shadow-xl': form.catering_id === catering.id, 'border-transparent hover:border-amber-200': form.catering_id !== catering.id}">
                            <img :src="catering.image" :alt="catering.name" class="w-full h-40 object-cover">
                            <div class="p-4 bg-white">
                                <h3 class="font-bold" x-text="catering.name"></h3>
                                <p class="text-sm text-gray-500" x-text="catering.type"></p>
                                <div class="flex justify-center mt-4">
                                    <button @click.stop="openModal(catering)"
                                        class="bg-amber-400 text-sm text-black font-bold py-1 px-3 rounded-full hover:bg-amber-500 transition">
                                        Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="flex justify-between mt-8">
                    <button @click="prevStep()"
                        class="bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-full hover:bg-gray-400 transition">Back</button>
                    <button @click="fetchVendorCategories(); nextStep()" :disabled="!form.catering_id"
                        :class="{'opacity-50 cursor-not-allowed': !form.catering_id}"
                        class="bg-amber-400 text-black font-bold py-2 px-6 rounded-full hover:bg-amber-500 transition">Next</button>
                </div>
            </div>

            <!-- Step 6+: Vendor Selection (Dynamic Steps) -->
            <template x-for="(category, index) in vendorCategories" :key="category.id">
                <div x-show="step === VENDOR_STEP_START + index" class="step-card p-8"
                    x-transition:enter="slide-in-right" x-transition:leave="slide-out-left"
                    x-init="markCategoryVisited(category.id)">
                    <h2 class="text-2xl font-bold mb-6 text-center" x-text="`Select Your ${category.name}`"></h2>
                    <div class="max-h-[60vh] overflow-y-auto p-2 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <template x-if="loading.vendors">
                                <p class="col-span-full text-center">Loading vendors...</p>
                            </template>
                            <template x-for="vendor in vendors[category.id]" :key="vendor.id">
                                <div @click="toggleVendor(vendor)"
                                    class="cursor-pointer border-4 rounded-lg overflow-hidden transition-all"
                                    :class="{'border-amber-400 shadow-xl': isVendorSelected(vendor.id), 'border-transparent hover:border-amber-300': !isVendorSelected(vendor.id)}">
                                    <img :src="vendor.image" :alt="vendor.name" class="w-full h-32 object-cover">
                                    <div class="p-4 bg-white">
                                        <h4 class="font-bold" x-text="vendor.name"></h4>
                                        <div class="flex justify-center mt-4">
                                            <button @click.stop="openModal(vendor)"
                                                class="bg-amber-400 text-sm text-black font-bold py-1 px-3 rounded-full hover:bg-amber-500 transition">
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="flex justify-between mt-8">
                        <button @click="prevStep()"
                            class="bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-full hover:bg-gray-400 transition">
                            Back
                        </button>

                        <!-- intermediate categories -->
                        <button x-show="index < vendorCategories.length - 1" @click="nextStep()"
                            class="bg-amber-400 text-black font-bold py-2 px-6 rounded-full hover:bg-amber-500 transition">
                            Next
                        </button>

                        <!-- last category: only requires that all have been seen -->
                        <button x-show="index === vendorCategories.length - 1" @click="fetchDiscounts(); nextStep()"
                            :disabled="visitedVendorCategories.length < vendorCategories.length" :class="{
                                            'opacity-50 cursor-not-allowed':
                                            visitedVendorCategories.length < vendorCategories.length
                                        }"
                            class="bg-amber-400 text-black font-bold py-2 px-6 rounded-full hover:bg-amber-500 transition">
                            Next
                        </button>
                    </div>

                </div>
            </template>

            <!-- Step 7: Discounts -->
            <div x-show="step === VENDOR_STEP_START + vendorCategories.length" class="step-card p-8"
                x-transition:enter="slide-in-right" x-transition:leave="slide-out-left">
                <h2 class="text-2xl font-bold mb-6 text-center">Available Discounts</h2>
                <div class="max-h-[60vh] overflow-y-auto p-2 space-y-4">
                    <template x-if="loading.discounts">
                        <p class="text-center">Checking for discounts...</p>
                    </template>
                    <template x-if="!loading.discounts && discounts.length === 0">
                        <p class="text-center">No discounts available for your current selection.</p>
                    </template>
                    <template x-for="discount in discounts" :key="discount.id">
                        <label
                            class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-amber-50 transition">
                            <input type="checkbox" :value="discount.id" x-model="form.discount_ids"
                                class="h-5 w-5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <div class="ml-4">
                                <p class="font-bold" x-text="discount.name"></p>
                                <p class="text-sm text-gray-600" x-text="discount.description"></p>
                            </div>
                        </label>
                    </template>
                </div>
                <div class="flex justify-between mt-8">
                    <button @click="prevStep()"
                        class="bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-full hover:bg-gray-400 transition">Back</button>
                    <button @click="submitForm()" :disabled="submitting"
                        class="bg-amber-400 text-black font-bold py-2 px-6 rounded-full hover:bg-amber-600 transition"
                        :class="{'opacity-50 cursor-wait': submitting}">
                        <span x-show="!submitting">Submit & View Recap</span>
                        <span x-show="submitting">Submitting...</span>
                    </button>
                </div>
            </div>

            <footer class="text-center text-sm py-4 text-gray-400 border-t mt-4 border-gray-100 bg-white">
                &copy; {{ date('Y') }} Lavanya Wedding Organizer
            </footer>

        </div>

        <!-- Modal -->
        <div x-show="isModalOpen" @keydown.escape.window="closeModal()" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-white/20 backdrop-blur-md p-4"
            style="display: none;">
            <div @click.away="closeModal()"
                class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="text-2xl font-bold" x-text="modalContent.name"></h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-800 text-3xl">&times;</button>
                </div>
                <div class="p-6 overflow-y-auto">
                    <img :src="modalContent.image" :alt="modalContent.name"
                        class="w-full h-64 object-cover rounded-lg mb-4">
                    <div class="prose max-w-none" x-html="modalContent.description || 'No description available.'">
                    </div>
                    <div class="mt-4">
                        <template x-if="modalContent.portofolio_link">
                            <a :href="modalContent.portofolio_link" target="_blank"
                                class="inline-block bg-amber-400 text-black font-bold py-2 px-4 rounded-full hover:bg-amber-500 transition">
                                View Portfolio
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const VENDOR_STEP_START = 6;
    </script>
    <script>
        function wizard() {
            return {
                step: 1,
                VENDOR_STEP_START,
                direction: 'forward',
                submitting: false,
                form: {
                    customer: {
                        grooms_name: '',
                        brides_name: '',
                        guest_count: null,
                        wedding_date: '',
                        phone_number: '',
                        referral_code: @json($referral->referral_code ?? ''),
                    },
                    venue_type: '',
                    venue_id: null,
                    catering_id: null,
                    vendors: [], // Will be array of objects {id, price, is_mandatory}
                    discount_ids: [],
                },

                // Data stores
                venueTypes: [],
                venues: [],
                caterings: [],
                vendorCategories: [],
                visitedVendorCategories: [], // <— NEW
                vendors: {}, // { category_id: [vendor1, vendor2] }
                discounts: [],
                initialReferralCode: '',

                loading: {
                    venues: false,
                    caterings: false,
                    vendorCategories: false,
                    vendors: false,
                    discounts: false,
                },

                init() {
                    this.initialReferralCode = this.form.customer.referral_code
                    this.fetchVenueTypes();
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    this.form.customer.wedding_date = `${yyyy}-${mm}-${dd}`;
                },

                // Navigation
                nextStep() { this.step++; },
                prevStep() { this.step--; },
                isModalOpen: false,
                modalContent: { name: '', description: '', image: '' },
                // Modal
                openModal(item) {
                    this.modalContent = {
                        name: item.name,
                        description: item.description || 'No description available.',
                        image: item.image,
                        portofolio_link: item.portofolio_link || ''
                    };
                    this.isModalOpen = true;
                },
                closeModal() {
                    this.isModalOpen = false;
                },

                // Validation
                isCustomerFormValid() {
                    const c = this.form.customer;
                    return c.grooms_name && c.brides_name && c.guest_count > 0 && c.wedding_date && c.phone_number;
                },
                markCategoryVisited(id) {
                    if (!this.visitedVendorCategories.includes(id)) {
                        this.visitedVendorCategories.push(id);
                    }
                },
                hasSelectedRequiredVendors() {
                    const allVendors = Object.values(this.vendors).flat();
                    const mandatoryVendors = allVendors.filter(v => v.is_mandatory);
                    return mandatoryVendors.every(mv => this.form.vendors.some(sv => sv.id === mv.id));
                },

                // Data Selection
                selectVenueType(type) { this.form.venue_type = type; },
                selectVenue(venue) { this.form.venue_id = venue.id; },
                selectCatering(catering) { this.form.catering_id = catering.id; },
                isVendorSelected(vendorId) { return this.form.vendors.some(v => v.id === vendorId); },
                toggleVendor(vendor) {
                    const index = this.form.vendors.findIndex(v => v.id === vendor.id);
                    if (index > -1) {
                        if (!vendor.is_mandatory) this.form.vendors.splice(index, 1);
                    } else {
                        this.form.vendors.push({ id: vendor.id, estimated_price: vendor.price, is_mandatory: vendor.is_mandatory });
                    }
                },

                // API Calls
                async fetchVenueTypes() {
                    const response = await fetch('/api/wizard/venue-types');
                    const data = await response.json();
                    this.venueTypes = data.types;
                },
                async fetchVenues() {
                    if (!this.form.venue_type || !this.form.customer.guest_count) return;
                    this.loading.venues = true;
                    const params = new URLSearchParams({
                        type: this.form.venue_type,
                        guest_count: this.form.customer.guest_count,
                        referral_code: this.form.customer.referral_code || ''
                    });
                    const response = await fetch(`/api/wizard/venues?${params}`);
                    const data = await response.json();
                    this.venues = data.venues;
                    this.loading.venues = false;
                },
                async fetchCaterings() {
                    if (!this.form.venue_id) return;
                    this.loading.caterings = true;
                    const params = new URLSearchParams({
                        venue_id: this.form.venue_id,
                        referral_code: this.form.customer.referral_code || ''
                    });
                    const response = await fetch(`/api/wizard/caterings?${params}`);
                    const data = await response.json();
                    this.caterings = data.caterings;
                    this.loading.caterings = false;
                },
                async fetchVendorCategories() {
                    if (!this.form.venue_id) return;
                    this.loading.vendorCategories = true;
                    const params = new URLSearchParams({
                        venue_id: this.form.venue_id,
                        referral_code: this.form.customer.referral_code || ''
                    });
                    const response = await fetch(`/api/wizard/vendor-categories?${params}`);
                    const data = await response.json();
                    this.vendorCategories = data.categories;
                    this.loading.vendorCategories = false;
                    this.fetchAllVendors();
                },
                async fetchAllVendors() {
                    this.vendors = {};
                    this.vendorCategories.forEach(cat => this.fetchVendorsForCategory(cat.id));
                },
                async fetchVendorsForCategory(categoryId) {
                    this.loading.vendors = true;
                    const params = new URLSearchParams({
                        category_id: categoryId,
                        venue_id: this.form.venue_id,
                        referral_code: this.form.customer.referral_code || ''
                    });
                    const response = await fetch(`/api/wizard/vendors?${params}`);
                    const data = await response.json();
                    this.vendors[categoryId] = data.vendors;
                    // Auto-select mandatory vendors
                    data.vendors.forEach(v => {
                        if (v.is_mandatory && !this.isVendorSelected(v.id)) {
                            this.toggleVendor(v);
                        }
                    });
                    this.loading.vendors = false;
                },
                async fetchDiscounts() {
                    this.loading.discounts = true;
                    const response = await fetch('/api/wizard/discounts', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({
                            venue_id: this.form.venue_id,
                            catering_id: this.form.catering_id,
                            vendor_ids: this.form.vendors.map(v => v.id)
                        })
                    });
                    const data = await response.json();
                    this.discounts = data.discounts;
                    this.loading.discounts = false;
                },
                async submitForm() {
                    if (this.submitting) return;
                    this.submitting = true;
                    try {
                        const response = await fetch('/api/wizard/store', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify(this.form)
                        });
                        if (!response.ok) {
                            const errorData = await response.json();
                            alert('Submission failed: ' + (errorData.message || 'Please check your inputs.'));
                            throw new Error('Submission failed');
                        }
                        const result = await response.json();
                        if (result.success) {
                            window.location.href = `/recap/${result.recap_link}`;
                        }
                    } catch (error) {
                        console.error('Submission error:', error);
                    } finally {
                        this.submitting = false;
                    }
                }
            }
        }
    </script>
</body>

</html>