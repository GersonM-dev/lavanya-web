@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto py-8">
        <!-- 1. CUSTOMER -->
        <div id="step-customer" class="form-section" style="display:block;">
            <h2 class="text-xl font-bold mb-4">Data Pengantin</h2>
            <form id="customer-form" autocomplete="off">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-2">
                    <div>
                        <label>Nama Pengantin Pria</label>
                        <input name="grooms_name" class="w-full rounded border px-3 py-2" required />
                    </div>
                    <div>
                        <label>Nama Pengantin Wanita</label>
                        <input name="brides_name" class="w-full rounded border px-3 py-2" required />
                    </div>
                    <div>
                        <label>Jumlah Tamu</label>
                        <input name="guest_count" type="number" min="1" class="w-full rounded border px-3 py-2" required />
                    </div>
                    <div>
                        <label>Tanggal Pernikahan</label>
                        <input name="wedding_date" type="date" class="w-full rounded border px-3 py-2" required />
                    </div>
                    <div>
                        <label>Kode Referral</label>
                        <input name="refferal_code" class="w-full rounded border px-3 py-2" />
                    </div>
                    <div>
                        <label>No. HP</label>
                        <input name="phone_number" class="w-full rounded border px-3 py-2" required />
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg">Next</button>
                </div>
            </form>
        </div>

        <!-- 2. VENUE TYPE -->
        <div id="step-venue-type" class="form-section" style="display:none;">
            <h2 class="text-xl font-bold mb-4">Pilih Tipe Venue</h2>
            <div id="venue-types-container" class="grid grid-cols-1 sm:grid-cols-3 gap-4"></div>
            <div class="flex justify-between mt-6">
                <button class="back-btn px-6 py-2 bg-gray-300 rounded-lg">Back</button>
                <button class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg" disabled>Next</button>
            </div>
        </div>

        <!-- 3. VENUE -->
        <div id="step-venue" class="form-section" style="display:none;">
            <h2 class="text-xl font-bold mb-4">Pilih Venue</h2>
            <div id="venues-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
            <div class="flex justify-between mt-6">
                <button class="back-btn px-6 py-2 bg-gray-300 rounded-lg">Back</button>
                <button class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg" disabled>Next</button>
            </div>
        </div>

        <!-- 4. VENDORS -->
        <div id="step-vendors" class="form-section" style="display:none;">
            <h2 class="text-xl font-bold mb-1">Pilih Vendor Kategori <span id="vendor-category-name"></span></h2>
            <div id="vendors-anim-wrapper">
                <div id="vendors-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
            </div>
            <div class="flex justify-between mt-6">
                <button class="back-btn px-6 py-2 bg-gray-300 rounded-lg">Back</button>
                <button class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg">Next</button>
            </div>
        </div>

        <!-- 5. CATERING -->
        <div id="step-catering" class="form-section" style="display:none;">
            <h2 class="text-xl font-bold mb-4">Pilih Catering</h2>
            <div id="caterings-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
            <div class="flex justify-between mt-6">
                <button class="back-btn px-6 py-2 bg-gray-300 rounded-lg">Back</button>
                <button class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg" disabled>Next</button>
            </div>
        </div>

        <!-- 6. DISCOUNTS -->
        <div id="step-discount" class="form-section" style="display:none;">
            <h2 class="text-xl font-bold mb-4">Pilih Diskon (jika ada)</h2>
            <div id="discounts-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
            <div class="flex justify-between mt-6">
                <button class="back-btn px-6 py-2 bg-gray-300 rounded-lg">Back</button>
                <button class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg">Next</button>
            </div>
        </div>

        <!-- 7. SUBMIT -->
        <div id="step-transaction" class="form-section" style="display:none;">
            <h2 class="text-xl font-bold mb-4">Konfirmasi & Submit</h2>
            <form id="wedding-form" autocomplete="off">
                <div class="text-sm text-gray-500 mb-2">Data Pengantin telah terisi otomatis.</div>
                <div class="flex justify-between mt-6">
                    <button type="button" class="back-btn px-6 py-2 bg-gray-300 rounded-lg">Back</button>
                    <button type="submit" class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg">Submit</button>
                </div>
            </form>
        </div>

        <!-- SUCCESS -->
        <div id="step-success" class="form-section text-center" style="display:none;">
            <h2 class="text-2xl font-bold mb-3">Pendaftaran Berhasil!</h2>
            <p>Terima kasih, data telah kami terima.</p>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-section {
            transition: transform 0.7s cubic-bezier(.4, 2, .6, 1), opacity 0.6s;
            will-change: transform, opacity;
            position: absolute;
            width: 100%;
            left: 0;
            top: 0;
            background: white;
            z-index: 10;
            min-height: 700px;
            opacity: 0;
            pointer-events: none;
        }

        .form-section.active {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(0);
            z-index: 50;
        }

        .swipe-left-in {
            transform: translateX(100vw);
            opacity: 0;
        }

        .swipe-left-out {
            transform: translateX(-100vw);
            opacity: 0;
        }

        .swipe-right-in {
            transform: translateX(-100vw);
            opacity: 0;
        }

        .swipe-right-out {
            transform: translateX(100vw);
            opacity: 0;
        }

        .max-w-xl {
            position: relative;
            min-height: 700px;
        }

        #vendors-anim-wrapper {
            transition: transform 0.7s cubic-bezier(.4, 2, .6, 1), opacity 0.6s;
            will-change: transform, opacity;
        }

        .vendors-slide-in {
            transform: translateX(100vw);
            opacity: 0;
        }

        .vendors-slide-out {
            transform: translateX(-100vw);
            opacity: 0;
        }

        .vendors-active {
            transform: translateX(0);
            opacity: 1;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        window.wizard = { venue_type: null, venue_id: null, vendor_categories: [], vendors: {}, catering_id: null, discounts: [], customer: {} };
        window.currentVendorCategoryIndex = 0;
        let currentSectionId = null;

        function animateSectionTransition(nextSectionId, direction = 'left') {
            const DURATION = 700;
            const allSections = document.querySelectorAll('.form-section');
            const nextSection = document.getElementById(nextSectionId);
            const currentSection = currentSectionId ? document.getElementById(currentSectionId) : null;

            if (currentSection && currentSection !== nextSection) {
                nextSection.style.display = 'block';
                nextSection.classList.remove('active', 'swipe-left-in', 'swipe-right-in', 'swipe-left-out', 'swipe-right-out');
                nextSection.classList.add(direction === 'left' ? 'swipe-left-in' : 'swipe-right-in');
                currentSection.classList.remove('active', 'swipe-left-in', 'swipe-right-in');
                currentSection.classList.add(direction === 'left' ? 'swipe-left-out' : 'swipe-right-out');
                setTimeout(() => {
                    currentSection.classList.remove('swipe-left-out', 'swipe-right-out');
                    currentSection.style.display = 'none';
                    nextSection.classList.remove('swipe-left-in', 'swipe-right-in');
                    nextSection.classList.add('active');
                }, DURATION);
            } else {
                nextSection.style.display = 'block';
                nextSection.classList.remove('swipe-left-in', 'swipe-right-in', 'swipe-left-out', 'swipe-right-out');
                nextSection.classList.add('active');
            }
            currentSectionId = nextSectionId;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function animateVendorsContent(direction = 'left', callback) {
            const wrapper = document.getElementById('vendors-anim-wrapper');
            wrapper.classList.remove('vendors-slide-in', 'vendors-slide-out', 'vendors-active');
            wrapper.classList.add(direction === 'left' ? 'vendors-slide-out' : 'vendors-slide-in');
            setTimeout(() => {
                if (typeof callback === 'function') callback();
                wrapper.classList.remove('vendors-slide-in', 'vendors-slide-out');
                wrapper.classList.add('vendors-active');
            }, 700);
        }

        // --- Wipe Gesture Support ---
        let startX = null;
        document.querySelector('.max-w-xl').addEventListener('touchstart', function (e) {
            startX = e.touches[0].clientX;
        });
        document.querySelector('.max-w-xl').addEventListener('touchmove', function (e) {
            if (startX === null) return;
            let moveX = e.touches[0].clientX;
            let diff = moveX - startX;
            if (Math.abs(diff) > 70) {
                if (diff > 0) { triggerSwipe('right'); }
                else { triggerSwipe('left'); }
                startX = null;
            }
        });
        function triggerSwipe(direction) {
            if (currentSectionId === 'step-customer' && direction === 'left')
                document.querySelector('#customer-form .next-btn').click();
            else if (currentSectionId === 'step-venue-type' && direction === 'right')
                animateSectionTransition('step-customer', 'right');
            else if (currentSectionId === 'step-venue-type' && direction === 'left')
                document.querySelector('#step-venue-type .next-btn').click();
            else if (currentSectionId === 'step-venue' && direction === 'right')
                document.querySelector('#step-venue .back-btn').click();
            else if (currentSectionId === 'step-venue' && direction === 'left')
                document.querySelector('#step-venue .next-btn').click();
            else if (currentSectionId === 'step-vendors' && direction === 'right')
                document.querySelector('#step-vendors .back-btn').click();
            else if (currentSectionId === 'step-vendors' && direction === 'left')
                document.querySelector('#step-vendors .next-btn').click();
            else if (currentSectionId === 'step-catering' && direction === 'right')
                document.querySelector('#step-catering .back-btn').click();
            else if (currentSectionId === 'step-catering' && direction === 'left')
                document.querySelector('#step-catering .next-btn').click();
            else if (currentSectionId === 'step-discount' && direction === 'right')
                document.querySelector('#step-discount .back-btn').click();
            else if (currentSectionId === 'step-discount' && direction === 'left')
                document.querySelector('#step-discount .next-btn').click();
            else if (currentSectionId === 'step-transaction' && direction === 'right')
                document.querySelector('#step-transaction .back-btn').click();
        }

        // --- Data Functions (API Fetching) ---
        function fetchVenueTypes() {
            axios.get('/api/wizard/venue-types').then(res => {
                const container = document.getElementById('venue-types-container');
                container.innerHTML = '';
                res.data.types.forEach(type => {
                    const card = document.createElement('div');
                    card.className = 'cursor-pointer p-4 rounded-lg shadow hover:ring-4 ring-indigo-400 text-center transition';
                    card.innerHTML = `<img src="${type.image}" class="w-full h-36 object-cover rounded mb-2">
                    <div class="font-bold text-lg">${type.name}</div>`;
                    card.onclick = () => {
                        window.wizard.venue_type = type.name;
                        [...container.children].forEach(c => c.classList.remove('ring-4', 'ring-indigo-400'));
                        card.classList.add('ring-4', 'ring-indigo-400');
                        document.querySelector('#step-venue-type .next-btn').disabled = false;
                    };
                    container.appendChild(card);
                });
            });
        }
        function fetchVenues() {
            axios.get('/api/wizard/venues', { params: { type: window.wizard.venue_type } })
                .then(res => {
                    const container = document.getElementById('venues-container');
                    container.innerHTML = '';
                    res.data.venues.forEach(venue => {
                        const card = document.createElement('div');
                        card.className = 'cursor-pointer p-4 rounded-lg shadow hover:ring-4 ring-indigo-400 text-center transition';
                        card.innerHTML = `<img src="${venue.image}" class="w-full h-36 object-cover rounded mb-2">
                        <div class="font-bold text-lg">${venue.name}</div>
                        <div class="text-gray-600">${venue.description}</div>`;
                        card.onclick = () => {
                            window.wizard.venue_id = venue.id;
                            [...container.children].forEach(c => c.classList.remove('ring-4', 'ring-indigo-400'));
                            card.classList.add('ring-4', 'ring-indigo-400');
                            document.querySelector('#step-venue .next-btn').disabled = false;
                        };
                        container.appendChild(card);
                    });
                });
        }
        function fetchVendorCategoriesAndFirst() {
            axios.get('/api/wizard/vendor-categories', { params: { venue_id: window.wizard.venue_id } })
                .then(res => {
                    window.wizard.vendor_categories = res.data.categories;
                    window.currentVendorCategoryIndex = 0;
                    fetchVendorsForCurrentCategory();
                    animateSectionTransition('step-vendors', 'left');
                });
        }
        function fetchVendorsForCurrentCategory() {
            const cat = window.wizard.vendor_categories[window.currentVendorCategoryIndex];
            document.getElementById('vendor-category-name').innerText = cat.name;
            axios.get('/api/wizard/vendors', { params: { category_id: cat.id, venue_id: window.wizard.venue_id } })
            .then(res => {
                const container = document.getElementById('vendors-container');
                container.innerHTML = '';
                if (!window.wizard.vendors[cat.id]) window.wizard.vendors[cat.id] = [];
                res.data.vendors.forEach(vendor => {
                const card = document.createElement('div');
                card.className = 'cursor-pointer p-4 rounded-lg shadow hover:ring-4 ring-indigo-400 text-center transition';
                card.innerHTML = `<img src="${vendor.image}" class="w-full h-28 object-cover rounded mb-2">
                <div class="font-bold">${vendor.name}</div>
                <div class="text-gray-600 text-xs mb-1">${vendor.description || ''}</div>
                ${vendor.is_mandatory ? '<span class="text-xs text-red-600 font-semibold">WAJIB</span>' : ''}`;
                if (window.wizard.vendors[cat.id].find(v => v.id === vendor.id)) {
                    card.classList.add('ring-4', 'ring-indigo-400');
                }
                card.onclick = () => {
                    const arr = window.wizard.vendors[cat.id];
                    const idx = arr.findIndex(v => v.id === vendor.id);
                    if (idx === -1) {
                    arr.push({ id: vendor.id, estimated_price: vendor.price || 0, is_mandatory: vendor.is_mandatory });
                    card.classList.add('ring-4', 'ring-indigo-400');
                    } else {
                    arr.splice(idx, 1);
                    card.classList.remove('ring-4', 'ring-indigo-400');
                    }
                };
                container.appendChild(card);
                });
            });
            document.getElementById('vendors-anim-wrapper').classList.remove('vendors-slide-in', 'vendors-slide-out');
            document.getElementById('vendors-anim-wrapper').classList.add('vendors-active');
        }
        function fetchCaterings() {
            axios.get('/api/wizard/caterings', { params: { venue_id: window.wizard.venue_id } })
                .then(res => {
                    const container = document.getElementById('caterings-container');
                    container.innerHTML = '';
                    res.data.caterings.forEach(cat => {
                        const card = document.createElement('div');
                        card.className = 'cursor-pointer p-4 rounded-lg shadow hover:ring-4 ring-indigo-400 text-center transition';
                        card.innerHTML = `<img src="${cat.image}" class="w-full h-28 object-cover rounded mb-2">
                        <div class="font-bold">${cat.name}</div>
                        <div class="text-xs text-gray-500">${cat.type}</div>`;
                        card.onclick = () => {
                            window.wizard.catering_id = cat.id;
                            [...container.children].forEach(c => c.classList.remove('ring-4', 'ring-indigo-400'));
                            card.classList.add('ring-4', 'ring-indigo-400');
                            document.querySelector('#step-catering .next-btn').disabled = false;
                        };
                        container.appendChild(card);
                    });
                });
        }
        function fetchDiscounts() {
            const vendorIds = [].concat(...Object.values(window.wizard.vendors)).map(v => v.id);
            axios.get('/api/wizard/discounts', {
                params: {
                    venue_id: window.wizard.venue_id,
                    catering_id: window.wizard.catering_id,
                    vendor_ids: vendorIds
                }
            }).then(res => {
                if (!res.data.discounts.length) {
                    animateSectionTransition('step-transaction', 'left'); // skip discount section
                    return;
                }
                const container = document.getElementById('discounts-container');
                container.innerHTML = '';
                window.wizard.discounts = [];
                res.data.discounts.forEach(dis => {
                    const card = document.createElement('div');
                    card.className = 'cursor-pointer p-4 rounded-lg shadow hover:ring-4 ring-green-400 text-center transition';
                    card.innerHTML = `<div class="font-bold">${dis.name}</div>
                        <div class="text-xs">${dis.description || ''}</div>';
                        <div class="text-indigo-700 font-semibold mb-1">Potongan: Rp${(dis.amount || 0).toLocaleString()}</div>`;
                    card.onclick = () => {
                        const idx = window.wizard.discounts.findIndex(d => d === dis.id);
                        if (idx === -1) {
                            window.wizard.discounts.push(dis.id);
                            card.classList.add('ring-4', 'ring-green-400');
                        } else {
                            window.wizard.discounts.splice(idx, 1);
                            card.classList.remove('ring-4', 'ring-green-400');
                        }
                    };
                    container.appendChild(card);
                });
            });
        }

        // ---- NAVIGATION ----
        document.addEventListener('DOMContentLoaded', function () {
            animateSectionTransition('step-customer');

            // Customer Info: Next
            document.getElementById('customer-form').onsubmit = function (e) {
                e.preventDefault();
                const data = {
                    grooms_name: document.querySelector('[name="grooms_name"]').value,
                    brides_name: document.querySelector('[name="brides_name"]').value,
                    guest_count: parseInt(document.querySelector('[name="guest_count"]').value),
                    wedding_date: document.querySelector('[name="wedding_date"]').value,
                    refferal_code: document.querySelector('[name="refferal_code"]').value,
                    phone_number: document.querySelector('[name="phone_number"]').value,
                };
                if (!data.grooms_name || !data.brides_name || !data.guest_count || !data.wedding_date || !data.phone_number) {
                    alert('Mohon lengkapi data pengantin.');
                    return false;
                }
                window.wizard.customer = data;
                animateSectionTransition('step-venue-type', 'left');
                fetchVenueTypes();
                return false;
            };
            // Venue Type: Back/Next
            document.querySelector('#step-venue-type .back-btn').onclick = () => animateSectionTransition('step-customer', 'right');
            document.querySelector('#step-venue-type .next-btn').onclick = function () {
                animateSectionTransition('step-venue', 'left');
                fetchVenues();
            };
            // Venue: Back/Next
            document.querySelector('#step-venue .back-btn').onclick = () => animateSectionTransition('step-venue-type', 'right');
            document.querySelector('#step-venue .next-btn').onclick = function () {
                fetchVendorCategoriesAndFirst();
            };
            // Vendors (per category)
            document.querySelector('#step-vendors .next-btn').onclick = function () {
                animateVendorsContent('left', function () {
                    window.currentVendorCategoryIndex++;
                    if (window.currentVendorCategoryIndex < window.wizard.vendor_categories.length) {
                        fetchVendorsForCurrentCategory();
                    } else {
                        animateSectionTransition('step-catering', 'left');
                        fetchCaterings();
                    }
                });
            };
            document.querySelector('#step-vendors .back-btn').onclick = function () {
                if (window.currentVendorCategoryIndex > 0) {
                    animateVendorsContent('right', function () {
                        window.currentVendorCategoryIndex--;
                        fetchVendorsForCurrentCategory();
                    });
                } else {
                    animateSectionTransition('step-venue', 'right');
                }
            };
            document.getElementById('vendors-anim-wrapper').classList.add('vendors-active');
            // Catering
            document.querySelector('#step-catering .back-btn').onclick = () => {
                if (window.wizard.vendor_categories.length > 0) {
                    window.currentVendorCategoryIndex = window.wizard.vendor_categories.length - 1;
                    animateSectionTransition('step-vendors', 'right');
                    fetchVendorsForCurrentCategory();
                } else {
                    animateSectionTransition('step-venue', 'right');
                }
            };
            document.querySelector('#step-catering .next-btn').onclick = function () {
                animateSectionTransition('step-discount', 'left');
                fetchDiscounts();
            };
            // Discount
            document.querySelector('#step-discount .back-btn').onclick = () => animateSectionTransition('step-catering', 'right');
            document.querySelector('#step-discount .next-btn').onclick = function () {
                animateSectionTransition('step-transaction', 'left');
            };
            // Transaction
            document.querySelector('#step-transaction .back-btn').onclick = () => animateSectionTransition('step-discount', 'right');
            // SUBMIT
            document.getElementById('wedding-form').onsubmit = function (e) {
                e.preventDefault();
                axios.post('/api/wizard/transaction', {
                    customer: window.wizard.customer,
                    venue_id: window.wizard.venue_id,
                    catering_id: window.wizard.catering_id,
                    vendors: Object.values(window.wizard.vendors).flat(),
                    discount_ids: window.wizard.discounts,
                }).then(res => {
                    if (res.data.success) {
                        animateSectionTransition('step-success', 'left');
                        if (res.data.transaction_id) {
                            setTimeout(() => {
                                window.open(`/wizard/recap/${res.data.transaction_id}`, '_blank');
                            }, 900);
                        }
                    }
                });
                return false;
            }
        });
    </script>
@endpush