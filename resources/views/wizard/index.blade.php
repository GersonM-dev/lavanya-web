@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-yellow-100 to-white">
        <div class="w-full max-w-xl">

            <!-- 0. LANDING PAGE -->
            <div id="step-landing" class="form-section active flex flex-col items-center p-6">
                <!-- Logo -->
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full max-w-2xl h-auto mb-8 mx-auto">
                <!-- CTA Button Centered -->
                <div class="w-full flex justify-center">
                    <button id="start-wizard-btn"
                        class="px-8 py-3 bg-amber-200 text-black rounded-lg text-xl shadow-lg hover:bg-amber-300 transition">
                        START
                    </button>
                </div>
            </div>

            <!-- 1. CUSTOMER -->
            <div id="step-customer" class="form-section bg-white rounded-lg shadow-lg p-6">
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
                            <input name="guest_count" type="number" min="1" class="w-full rounded border px-3 py-2"
                                required />
                        </div>
                        <div>
                            <label>Tanggal Pernikahan</label>
                            <input name="wedding_date" type="date" class="w-full rounded border px-3 py-2" required />
                        </div>
                        <div>
                            <label>Kode Referral</label>
                            <input name="refferal_code" id="refferal_code_input" class="w-full rounded border px-3 py-2" />
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
            <div id="step-venue-type" class="form-section bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Pilih Tipe Venue</h2>
                <div id="venue-types-container" class="grid grid-cols-1 sm:grid-cols-3 gap-4"></div>
                <div class="flex justify-between mt-6">
                    <button class="back-btn px-6 py-2 bg-gray-300 rounded-lg">Back</button>
                    <button class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg" disabled>Next</button>
                </div>
            </div>

            <!-- 3. VENUE -->
            <div id="step-venue" class="form-section bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Pilih Venue</h2>
                <div id="venues-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
                <div class="flex justify-between mt-6">
                    <button class="back-btn px-6 py-2 bg-gray-300 rounded-lg">Back</button>
                    <button class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg" disabled>Next</button>
                </div>
            </div>

            <!-- 4. CATERING -->
            <div id="step-catering" class="form-section bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Pilih Catering</h2>
                <div id="caterings-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
                <div class="flex justify-between mt-6">
                    <button class="back-btn px-6 py-2 bg-gray-300 rounded-lg">Back</button>
                    <button class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg" disabled>Next</button>
                </div>
            </div>

            <!-- 5. VENDORS -->
            <div id="step-vendors" class="form-section bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-1">Pilih Vendor Kategori <span id="vendor-category-name"></span></h2>
                <div id="vendors-anim-wrapper">
                    <div id="vendors-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
                </div>
                <div class="flex justify-between mt-6">
                    <button class="back-btn px-6 py-2 bg-gray-300 rounded-lg">Back</button>
                    <button class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg">Next</button>
                </div>
            </div>

            <!-- 6. DISCOUNTS -->
            <div id="step-discount" class="form-section bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Pilih Diskon (jika ada)</h2>
                <div id="discounts-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
                <div class="flex justify-between mt-6">
                    <button class="back-btn px-6 py-2 bg-gray-300 rounded-lg">Back</button>
                    <button class="next-btn px-6 py-2 bg-indigo-600 text-white rounded-lg">Next</button>
                </div>
            </div>

            <!-- 7. SUBMIT -->
            <div id="step-transaction" class="form-section bg-white rounded-lg shadow-lg p-6">
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
            <div id="step-success" class="form-section text-center">
                <h2 class="text-2xl font-bold mb-3">Terima kasih, data telah kami terima.</h2>
                <p>Tim Marketing kami akan segera menghubungi</p>
            </div>

            <!-- Modal for Detail -->
            <div id="detail-modal-backdrop" class="fixed inset-0 bg-black/40 z-50 hidden"></div>
            <div id="detail-modal"
                class="fixed left-1/2 top-1/2 z-50 bg-white rounded-xl shadow-xl p-6 w-full max-w-lg hidden"
                style="transform:translate(-50%,-50%)">
                <button onclick="closeDetailModal()"
                    class="absolute top-2 right-3 text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
                <div id="detail-modal-content"></div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-section {
            display: none !important;
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.5s, transform 0.5s;
        }

        .form-section.active {
            display: block !important;
            opacity: 1;
            transform: translateY(0);
            z-index: 1;
        }

        .max-w-xl {
            position: relative;
            min-height: 700px;
        }

        #detail-modal {
            transition: opacity .2s, transform .2s;
        }

        #detail-modal-backdrop {
            transition: opacity .2s;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- get referral slug from URL -->
    <script>
        // Detect slug from URL like https://domain.com/{slug}
        function getReferralSlug() {
            // window.location.pathname returns e.g. "/abc123"
            var path = window.location.pathname;
            // Remove leading and trailing slashes, get the first segment
            var slug = path.replace(/^\/|\/$/g, '');
            // If your homepage is also '/', only set slug if it's not empty
            if (slug && slug !== '' && slug !== 'wizard') return slug;
            return null;
        }

        window.referralId = getReferralSlug();

        document.addEventListener('DOMContentLoaded', function () {
            // Autofill and readonly if referralId exists
            if (window.referralId) {
                var refInput = document.querySelector('[name="refferal_code"]');
                if (refInput) {
                    refInput.value = window.referralId;
                    refInput.readOnly = true;
                    refInput.classList.add('bg-gray-100', 'text-gray-600');
                }
            }
        });
    </script>

    <!-- step by step wizard logic -->
    <script>
        window.wizard = { venue_type: null, venue_id: null, vendor_categories: [], vendors: {}, catering_id: null, discounts: [], customer: {} };
        window.currentVendorCategoryIndex = 0;
        let currentSectionId = null;

        function animateSectionTransition(nextSectionId) {
            document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
            document.getElementById(nextSectionId).classList.add('active');
            currentSectionId = nextSectionId;
            window.scrollTo({ top: 0 });
        }

        function animateVendorsContent(_, callback) {
            if (typeof callback === 'function') callback();
        }

        function fetchVenueTypes() {
            axios.get('/api/wizard/venue-types').then(res => {
                const container = document.getElementById('venue-types-container');
                container.innerHTML = '';
                res.data.types.forEach(type => {
                    const card = document.createElement('div');
                    card.className = 'cursor-pointer p-4 rounded-lg shadow hover:ring-4 ring-indigo-400 text-center transition';
                    card.innerHTML = `
                                        <div class="aspect-[4/3] w-full rounded mb-2 overflow-hidden">
                                            <img src="${type.image}" class="w-full h-full object-cover" />
                                        </div>
                                        <div class="font-bold text-lg">${type.name}</div>
                                    `;
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
            const guestCount = window.wizard.customer?.guest_count || 0;
            axios.get('/api/wizard/venues', {
                params: {
                    type: window.wizard.venue_type,
                    guest_count: guestCount,
                    referral_id: window.referralId,
                }
            }).then(res => {
                const container = document.getElementById('venues-container');
                container.innerHTML = '';
                res.data.venues.forEach(venue => {
                    const card = document.createElement('div');
                    card.className = 'cursor-pointer p-4 rounded-lg shadow hover:ring-4 ring-amber-400 text-center transition';
                    card.innerHTML = `
                                        <div class="aspect-[4/3] w-full rounded mb-2 overflow-hidden">
                                            <img src="${venue.image}" class="w-full h-full object-cover" />
                                        </div>
                                        <div class="font-bold text-lg">${venue.name}</div>
                                        <button type="button" class="detail-btn mt-2 px-3 py-1 rounded bg-indigo-500 text-white text-xs"
                                            onclick="showDetailModalFromBtn(event, '${encodeURIComponent(JSON.stringify(venue))}', 'venue')">
                                            Detail
                                        </button>
                                    `;
                    card.onclick = function (e) {
                        if (e.target.classList.contains('detail-btn')) return; // don't select on detail button click
                        window.wizard.venue_id = venue.id;
                        [...container.children].forEach(c => c.classList.remove('ring-4', 'ring-amber-400'));
                        card.classList.add('ring-4', 'ring-amber-400');
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
            axios.get('/api/wizard/vendors', { params: { category_id: cat.id, venue_id: window.wizard.venue_id, referral_id: window.referralId } })
                .then(res => {
                    const container = document.getElementById('vendors-container');
                    container.innerHTML = '';
                    if (!window.wizard.vendors[cat.id]) window.wizard.vendors[cat.id] = [];
                    res.data.vendors.forEach(vendor => {
                        const card = document.createElement('div');
                        card.className = 'cursor-pointer p-4 rounded-lg shadow hover:ring-4 ring-indigo-400 text-center transition';
                        card.innerHTML = `
                                            <div class="aspect-[4/3] w-full rounded mb-2 overflow-hidden">
                                                <img src="${vendor.image}" class="w-full h-full object-cover" />
                                            </div>
                                            <div class="font-bold">${vendor.name}</div>
                                            ${vendor.is_mandatory ? '<span class="text-xs text-red-600 font-semibold">WAJIB</span>' : ''}
                                            <button type="button" class="detail-btn mt-2 px-3 py-1 rounded bg-indigo-500 text-white text-xs"
                                                onclick="showDetailModalFromBtn(event, '${encodeURIComponent(JSON.stringify(vendor))}', 'vendor')">
                                                Detail
                                            </button>
                                        `;
                        if (window.wizard.vendors[cat.id].find(v => v.id === vendor.id)) {
                            card.classList.add('ring-4', 'ring-indigo-400');
                        }
                        card.onclick = function (e) {
                            if (e.target.classList.contains('detail-btn')) return;
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
            axios.get('/api/wizard/caterings', { params: { venue_id: window.wizard.venue_id, referral_id: window.referralId } })
                .then(res => {
                    const container = document.getElementById('caterings-container');
                    container.innerHTML = '';
                    res.data.caterings.forEach(cat => {
                        const card = document.createElement('div');
                        card.className = 'cursor-pointer p-4 rounded-lg shadow hover:ring-4 ring-indigo-400 text-center transition';
                        card.innerHTML = `
                                            <div class="aspect-[4/3] w-full rounded mb-2 overflow-hidden">
                                                <img src="${cat.image}" class="w-full h-full object-cover" />
                                            </div>
                                            <div class="font-bold">${cat.name}</div>
                                            <div class="text-xs text-gray-500 truncate">${cat.type}</div>
                                            <button type="button" class="detail-btn mt-2 px-3 py-1 rounded bg-indigo-500 text-white text-xs"
                                                onclick="showDetailModalFromBtn(event, '${encodeURIComponent(JSON.stringify(cat))}', 'catering')">
                                                Detail
                                            </button>
                                        `;
                        card.onclick = function (e) {
                            if (e.target.classList.contains('detail-btn')) return;
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
                                                                                                <div class="text-xs">${dis.description || ''}</div>
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

        function showDetailModalFromBtn(e, itemJson, type) {
            e.stopPropagation();
            const item = JSON.parse(decodeURIComponent(itemJson));
            showDetailModal(item, type);
        }

        function showDetailModal(item, type) {
            // Support both possible property spellings for description and portfolio
            const desc = item.description || item.deskripsi || '';
            const portfolio = item.portofolio_link || item.portfolio_link || '';

            // Responsive grid for up to 3 images, each with 4:3 aspect ratio
            let imagesHtml = '';
            [item.image, item.image2, item.image3].forEach(img => {
                if (img) {
                    imagesHtml += `
                        <div class="aspect-[4/3] w-40 bg-gray-100 rounded-lg overflow-hidden shadow">
                            <img src="${img}" class="w-full h-full object-cover" />
                        </div>
                    `;
                }
            });

            // Badges for type, capacity, or "WAJIB"
            let infoBadges = '';
            if (type === 'catering' && item.type) {
                infoBadges += `<span class="inline-block bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-semibold mb-2">${item.type}</span>`;
            }
            if (type === 'venue' && item.capacity) {
                infoBadges += `<span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold mb-2 ml-2">Kapasitas: ${item.capacity}</span>`;
            }
            if (type === 'vendor' && item.is_mandatory) {
                infoBadges += `<span class="inline-block bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold mb-2 ml-2">WAJIB</span>`;
            }

            // Price badge if available
            // let priceBadge = '';
            // if (typeof item.price !== 'undefined' && item.price !== null && item.price !== '') {
            //     priceBadge = `<span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold mb-2 ml-2">Rp${Number(item.price).toLocaleString()}</span>`;
            // }

            // Portfolio link (only if exists)
            let portfolioHtml = '';
            if (portfolio) {
                portfolioHtml = `<a href="${portfolio}" class="text-blue-600 underline hover:text-blue-800 mb-2 inline-block mt-3 transition" target="_blank">Lihat Portofolio</a>`;
            }

            let html = `
                <div class="text-center px-1">
                    <!-- Images grid -->
                    <div class="flex flex-wrap gap-2 justify-center mb-4">
                        ${imagesHtml || `<div class="w-40 aspect-[4/3] flex items-center justify-center bg-gray-50 rounded">No Image</div>`}
                    </div>
                    <!-- Title -->
                    <div class="font-bold text-xl mb-1">${item.name || ''}</div>
                    <!-- Info badges -->
                    <div class="flex flex-wrap gap-2 justify-center mb-2">${infoBadges}${priceBadge}</div>
                    <!-- Description -->
                    <div class="mb-3 text-gray-700 max-h-40 overflow-auto px-1" style="word-break:break-word;">${desc}</div>
                    <!-- Portfolio link -->
                    ${portfolioHtml}
                </div>
            `;

            document.getElementById('detail-modal-content').innerHTML = html;
            document.getElementById('detail-modal').classList.remove('hidden');
            document.getElementById('detail-modal-backdrop').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detail-modal').classList.add('hidden');
            document.getElementById('detail-modal-backdrop').classList.add('hidden');
        }

        document.getElementById('detail-modal-backdrop').onclick = closeDetailModal;

        document.addEventListener('DOMContentLoaded', function () {
            animateSectionTransition('step-landing');

            document.getElementById('start-wizard-btn').onclick = function () {
                animateSectionTransition('step-customer', 'left');
            };

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
                animateSectionTransition('step-catering', 'left');
                fetchCaterings();
            };

            // Vendors (per category)
            document.querySelector('#step-vendors .next-btn').onclick = function () {
                animateVendorsContent('left', function () {
                    window.currentVendorCategoryIndex++;
                    if (window.currentVendorCategoryIndex < window.wizard.vendor_categories.length) {
                        fetchVendorsForCurrentCategory();
                    } else {
                        animateSectionTransition('step-discount', 'left');
                        fetchDiscounts();
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
                    animateSectionTransition('step-catering', 'right');
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
                fetchVendorCategoriesAndFirst();
            };

            // Discount
            document.querySelector('#step-discount .back-btn').onclick = () => animateSectionTransition('step-vendors', 'right');

            document.querySelector('#step-discount .next-btn').onclick = function () {
                animateSectionTransition('step-transaction', 'left');
            };
            // Transaction
            document.querySelector('#step-transaction .back-btn').onclick = () => animateSectionTransition('step-discount', 'right');
            // Submit
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
                        if (res.data.recap_link) {
                            setTimeout(() => {
                                window.location.href = '/recap/' + res.data.recap_link;
                            }, 900);
                        }
                    }
                }).catch(err => {
                    // Handle and show the error
                    if (err.response && err.response.data && err.response.data.errors) {
                        let message = Object.values(err.response.data.errors).flat().join('\n');
                        alert(message);
                    } else {
                        alert("Submit failed: " + (err.response?.data?.message || 'Unknown error'));
                    }
                });
                return false;
            }
        });
    </script>
@endpush