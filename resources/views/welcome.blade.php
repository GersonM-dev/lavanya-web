<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lavanya Wedding Organizer</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            font-family: 'Cinzel', serif;
        }

        .form-section {
            position: absolute;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 10;
        }

        .form-section {
            opacity: 0;
            pointer-events: none;
            transition: transform 0.5s cubic-bezier(.77, 0, .18, 1), opacity 0.35s;
        }

        .form-section.active {
            opacity: 1;
            pointer-events: auto;
            position: relative;
            z-index: 20;
        }

        /* Animations for swipe */
        .slide-in-right {
            transform: translateX(100%);
        }

        .slide-in-left {
            transform: translateX(-100%);
        }

        .slide-out-left {
            transform: translateX(-100%);
            opacity: 0;
        }

        .slide-out-right {
            transform: translateX(100%);
            opacity: 0;
        }

        .active,
        .slide-to-center {
            transform: translateX(0);
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="bg-gradient-to-tr from-slate-50 via-yellow-100 to-slate-50 py-6 sm:py-8 lg:py-12 min-h-screen relative overflow-x-hidden"
        style="min-height:100vh">
        <div class="mx-auto max-w-screen-lg px-4 md:px-8 relative" style="min-height: 650px;">
            <form id="wedding-form" class="space-y-8 relative min-h-[540px]">
                <!-- Landing Page (Step 0) -->
                <div class="form-section bg-white rounded-lg shadow-xl p-12 border border-gray-200 flex flex-col items-center justify-center active"
                    style="display: block;">
                    <div class="w-full flex flex-col items-center">
                        <div class="mb-4">
                            <svg width="70" height="70" fill="none" viewBox="0 0 64 64">
                                <circle cx="32" cy="32" r="32" fill="#fef9c3" />
                                <text x="32" y="42" text-anchor="middle" font-size="36" fill="#d97706"
                                    font-family="Cinzel, serif">L</text>
                            </svg>
                        </div>
                        <h1 class="text-4xl font-bold mb-2 text-center text-indigo-800">Lavanya Wedding Organizer</h1>
                        <p class="text-xl text-gray-700 mb-6 text-center max-w-lg">Plan your dream wedding with elegance
                            and ease.<br />Start your journey with us.</p>
                        <button type="button"
                            class="plan-btn bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-xl text-lg font-bold shadow-lg transition-all duration-150">
                            Plan Your Wedding
                        </button>
                    </div>
                </div>
                <!-- Section: Customer Info -->
                <div class="form-section bg-white rounded-lg shadow-lg p-8 border border-gray-200"
                    style="display: none;">
                    <h2 class="text-2xl font-bold mb-2">Customer Info</h2>
                    <p class="text-sm text-gray-500 mb-6">Masukkan informasi pelanggan di bawah ini</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Nama Pengantin Pria *</label>
                            <input type="text" name="customer.grooms_name" required
                                class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring" />
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Nama Pengantin Wanita *</label>
                            <input type="text" name="customer.brides_name" required
                                class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring" />
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Nomor HP *</label>
                            <input type="text" name="customer.phone_number" required
                                class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring" />
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Kode Referral</label>
                            <input type="text" name="customer.refferal_code"
                                class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring" />
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Jumlah Tamu *</label>
                            <input type="number" min="0" name="customer.guest_count" required
                                class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring" />
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Tanggal Pernikahan *</label>
                            <input type="date" name="customer.wedding_date" required
                                class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring" />
                        </div>
                    </div>
                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="back-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold">Back</button>
                        <button type="button"
                            class="next-btn bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold">Next</button>
                    </div>
                </div>
                <!-- Section: Venue Info -->
                <div class="form-section bg-white rounded-lg shadow-lg p-8 border border-gray-200"
                    style="display: none;">
                    <h2 class="text-2xl font-bold mb-2">Venue Info</h2>
                    <p class="text-sm text-gray-500 mb-6">Pilih venue untuk acara pernikahan</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Tipe Venue *</label>
                            <select name="venue_type" required
                                class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring">
                                <option value="" selected disabled>Pilih tipe venue</option>
                                <option value="Indoor">Indoor</option>
                                <option value="Outdoor">Outdoor</option>
                                <option value="Semi-Outdoor">Semi-Outdoor</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Venue *</label>
                            <select name="venue_id" required
                                class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring">
                                <option value="" selected disabled>Pilih venue</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="back-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold">Back</button>
                        <button type="button"
                            class="next-btn bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold">Next</button>
                    </div>
                </div>
                <!-- Section: Catering Info -->
                <div class="form-section bg-white rounded-lg shadow-lg p-8 border border-gray-200"
                    style="display: none;">
                    <h2 class="text-2xl font-bold mb-2">Catering Info</h2>
                    <p class="text-sm text-gray-500 mb-6">Pilih vendor catering untuk acara pernikahan</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Vendor Catering *</label>
                            <select name="catering_id" required
                                class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring">
                                <option value="" selected disabled>Pilih catering</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Harga Buffet</label>
                            <div class="flex items-center">
                                <span class="mr-2 text-gray-500">IDR</span>
                                <input type="number" name="total_buffet_price" value="0" disabled
                                    class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none" />
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Harga Gubugan</label>
                            <div class="flex items-center">
                                <span class="mr-2 text-gray-500">IDR</span>
                                <input type="number" name="total_gubugan_price" value="0" disabled
                                    class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none" />
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Harga Dessert</label>
                            <div class="flex items-center">
                                <span class="mr-2 text-gray-500">IDR</span>
                                <input type="number" name="total_dessert_price" value="0" disabled
                                    class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none" />
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Total Biaya Catering</label>
                            <div class="flex items-center">
                                <span class="mr-2 text-gray-500">IDR</span>
                                <input type="number" name="catering_total_price" value="0" disabled
                                    class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none" />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="back-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold">Back</button>
                        <button type="button"
                            class="next-btn bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold">Next</button>
                    </div>
                </div>
                <!-- Section: Vendor Info -->
                <div class="form-section bg-white rounded-lg shadow-lg p-8 border border-gray-200"
                    style="display: none;">
                    <h2 class="text-2xl font-bold mb-2">Vendor Info</h2>
                    <p class="text-sm text-gray-500 mb-6">Pilih vendor untuk acara pernikahan</p>
                    <div id="vendors-repeater" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border rounded p-4 bg-gray-50">
                            <div>
                                <label class="block mb-2 text-sm text-gray-800">Vendor *</label>
                                <select name="vendor_id[]" required
                                    class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring">
                                    <option value="" selected disabled>Pilih vendor</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm text-gray-800">Estimated Price *</label>
                                <input type="number" min="0" name="estimated_price[]" required
                                    class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring" />
                            </div>
                        </div>
                        <button type="button"
                            class="mt-2 px-4 py-2 bg-indigo-100 rounded hover:bg-indigo-200 text-indigo-600 text-sm font-semibold">Tambah
                            Vendor</button>
                    </div>
                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="back-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold">Back</button>
                        <button type="button"
                            class="next-btn bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold">Next</button>
                    </div>
                </div>
                <!-- Section: Discounts -->
                <div class="form-section bg-white rounded-lg shadow-lg p-8 border border-gray-200"
                    style="display: none;">
                    <h2 class="text-2xl font-bold mb-2">Discounts</h2>
                    <p class="text-sm text-gray-500 mb-6">Pilih diskon yang berlaku sesuai pilihan vendor, venue, dan
                        catering</p>
                    <div>
                        <label class="block mb-2 text-sm text-gray-800">Discounts</label>
                        <select name="discounts[]" multiple
                            class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring">
                            <option value="1">Sample Discount 1</option>
                            <option value="2">Sample Discount 2</option>
                        </select>
                    </div>
                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="back-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold">Back</button>
                        <button type="button"
                            class="next-btn bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold">Next</button>
                    </div>
                </div>
                <!-- Section: Transaction Info -->
                <div class="form-section bg-white rounded-lg shadow-lg p-8 border border-gray-200"
                    style="display: none;">
                    <h2 class="text-2xl font-bold mb-2">Transaction Info</h2>
                    <p class="text-sm text-gray-500 mb-6">Informasi transaksi dan total harga</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Total Harga Seluruh</label>
                            <div class="flex items-center">
                                <span class="mr-2 text-gray-500">IDR</span>
                                <input type="number" name="total_estimated_price" value="0" disabled
                                    class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none" />
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-800">Transaction Date</label>
                            <input type="datetime-local" name="transaction_date" disabled value="2024-07-04T00:00"
                                class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block mb-2 text-sm text-gray-800">Notes</label>
                        <textarea name="notes" rows="3"
                            class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 focus:ring"></textarea>
                    </div>
                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="back-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold">Back</button>
                        <button type="submit"
                            class="bg-indigo-500 hover:bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        const sections = document.querySelectorAll('.form-section');
        let current = 0;
        let isTransitioning = false;

        function resetAnimation(section) {
            section.classList.remove(
                'slide-in-left', 'slide-in-right',
                'slide-out-left', 'slide-out-right',
                'slide-to-center', 'active'
            );
        }

        function showSection(next, direction = 1) {
            if (next === current || isTransitioning) return;
            isTransitioning = true;

            const outSection = sections[current];
            const inSection = sections[next];
            resetAnimation(outSection);

            // Prepare incoming section: visible, above old, ready to slide
            inSection.style.display = "block";
            inSection.style.zIndex = 30;
            resetAnimation(inSection);

            if (direction === 1) {
                outSection.classList.add('slide-out-left');
                inSection.classList.add('slide-in-right');
            } else {
                outSection.classList.add('slide-out-right');
                inSection.classList.add('slide-in-left');
            }

            // Force reflow (guarantees browser sees style changes before animating)
            void inSection.offsetWidth;
            void outSection.offsetWidth;

            // Slide in to center
            setTimeout(() => {
                resetAnimation(inSection);
                inSection.classList.add('slide-to-center', 'active');
                inSection.style.display = "block";
                inSection.style.zIndex = 30;
            }, 10);

            // Clean up previous after transition
            setTimeout(() => {
                resetAnimation(outSection);
                outSection.style.display = "none";
                outSection.style.zIndex = 10;
                inSection.classList.add('slide-to-center', 'active');
                inSection.style.display = "block";
                inSection.style.zIndex = 20;
                isTransitioning = false;
                current = next;
            }, 510);

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }


        // Plan Your Wedding CTA
        document.querySelector('.plan-btn').addEventListener('click', function () {
            showSection(1, 1); // Forward
        });

        // Next/Back buttons
        document.querySelectorAll('.next-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (current < sections.length - 1) showSection(current + 1, 1);
            });
        });
        document.querySelectorAll('.back-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (current > 1) showSection(current - 1, -1);
                else showSection(0, -1); // Back to landing
            });
        });

        // Prevent Enter from submitting in steps (except final step)
        document.getElementById('wedding-form').addEventListener('keydown', function (e) {
            if (e.key === "Enter" && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        });

        // Set default transaction date to now
        document.querySelector('input[name="transaction_date"]').value = new Date().toISOString().slice(0, 16);

        // On load, show first section
        document.addEventListener("DOMContentLoaded", () => {
            for (let i = 1; i < sections.length; i++) {
                sections[i].style.display = "none";
                resetAnimation(sections[i]);
            }
            sections[0].classList.add('active', 'slide-to-center');
            sections[0].style.display = "block";
        });

    </script>

</body>

</html>