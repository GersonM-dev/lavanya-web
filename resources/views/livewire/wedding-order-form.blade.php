<x-app-layout>
    <div>
        <div class="w-full max-w-2xl mx-auto my-10">
            <div class="flex justify-center mb-6">
                <!-- Stepper -->
                <div class="flex items-center gap-4">
                    @foreach([1 => 'Customer', 2 => 'Venue', 3 => 'Catering', 4 => 'Vendors'] as $num => $label)
                        <div class="flex flex-col items-center">
                            <div
                                class="{{ $step == $num ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-500' }} rounded-full w-8 h-8 flex items-center justify-center font-bold">
                                {{ $num }}
                            </div>
                            <div class="text-center text-xs mt-1">{{ $label }}</div>
                        </div>
                        @if($num < 4)
                            <div class="w-8 border-t-2 {{ $step > $num ? 'border-teal-600' : 'border-gray-200' }}"></div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div x-data="{ step: @entangle('step') }" class="relative bg-white shadow rounded-2xl overflow-hidden"
                style="min-height: 420px;">
                {{-- Step 1 --}}
                <div x-show="step === 1" x-transition:enter="transition duration-500"
                    x-transition:enter-start="opacity-0 translate-x-10"
                    x-transition:enter-end="opacity-100 translate-x-0" class="px-8 py-6">
                    <h3 class="font-bold text-xl mb-4">Customer Info</h3>
                    <div class="mb-3">
                        <input wire:model="grooms_name" class="input" placeholder="Nama Pengantin Pria">
                        @error('grooms_name') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <input wire:model="brides_name" class="input" placeholder="Nama Pengantin Wanita">
                        @error('brides_name') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <input wire:model="phone_number" class="input" placeholder="Nomor HP">
                        @error('phone_number') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <input wire:model="guest_count" type="number" class="input" placeholder="Jumlah Tamu">
                        @error('guest_count') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <input wire:model="wedding_date" type="date" class="input" placeholder="Tanggal Pernikahan">
                        @error('wedding_date') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <button wire:click="nextStep" class="btn btn-teal mt-4 float-right">Next</button>
                </div>

                {{-- Step 2 --}}
                <div x-show="step === 2" x-transition:enter="transition duration-500"
                    x-transition:enter-start="opacity-0 translate-x-10"
                    x-transition:enter-end="opacity-100 translate-x-0" class="px-8 py-6 absolute inset-0 bg-white">
                    <h3 class="font-bold text-xl mb-4">Venue Selection</h3>
                    <div class="mb-3">
                        <select wire:model="venue_type" class="input">
                            <option value="">Pilih Tipe Venue</option>
                            <option value="Indoor">Indoor</option>
                            <option value="Outdoor">Outdoor</option>
                            <option value="Semi-Outdoor">Semi-Outdoor</option>
                        </select>
                        @error('venue_type') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <select wire:model="venue_id" class="input">
                            <option value="">Pilih Venue</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}">{{ $venue->nama }}</option>
                            @endforeach
                        </select>
                        @error('venue_id') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <button wire:click="prevStep" class="btn btn-gray mr-2">Back</button>
                    <button wire:click="nextStep" class="btn btn-teal">Next</button>
                </div>

                {{-- Step 3 --}}
                <div x-show="step === 3" x-transition:enter="transition duration-500"
                    x-transition:enter-start="opacity-0 translate-x-10"
                    x-transition:enter-end="opacity-100 translate-x-0" class="px-8 py-6 absolute inset-0 bg-white">
                    <h3 class="font-bold text-xl mb-4">Catering Selection</h3>
                    <div class="mb-3">
                        <select wire:model="catering_id" class="input">
                            <option value="">Pilih Catering</option>
                            @foreach($caterings as $catering)
                                <option value="{{ $catering->id }}">{{ $catering->nama }}</option>
                            @endforeach
                        </select>
                        @error('catering_id') <span class="text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <input class="input" value="Buffet: {{ number_format($buffet_price) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <input class="input" value="Gubugan: {{ number_format($gubugan_price) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <input class="input" value="Dessert: {{ number_format($dessert_price) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <input class="input" value="Total Catering: {{ number_format($catering_total_price) }}"
                            readonly>
                    </div>
                    <button wire:click="prevStep" class="btn btn-gray mr-2">Back</button>
                    <button wire:click="nextStep" class="btn btn-teal">Next</button>
                </div>

                {{-- Step 4: Vendor Selection (multi-category sub-steps) --}}
                <div x-show="step === 4" x-transition:enter="transition duration-500"
                    x-transition:enter-start="opacity-0 translate-x-10"
                    x-transition:enter-end="opacity-100 translate-x-0" class="px-8 py-6 absolute inset-0 bg-white">
                    @php
                        $vendorCategories = $vendor_categories ?? [];
                        $currentCat = $vendorCategories[$vendor_step] ?? null;
                        $categoryCount = count($vendorCategories);
                    @endphp

                    @if($currentCat)
                        <h3 class="font-bold text-xl mb-4">
                            Pilih Vendor {{ $currentCat['name'] }}
                            <span class="text-xs text-gray-400">(Step {{ $vendor_step + 1 }} dari
                                {{ $categoryCount }})</span>
                        </h3>

                        <div class="mb-4">
                            @forelse($currentCat['vendors'] as $vendor)
                                <label class="block border rounded-lg p-3 mb-2 cursor-pointer transition hover:bg-teal-50">
                                    <input type="radio" wire:model="selected_vendors.{{ $currentCat['id'] }}"
                                        value="{{ $vendor['id'] }}" class="mr-2">
                                    <span class="font-medium">{{ $vendor['nama'] }}</span>
                                    <span class="ml-2 text-xs text-gray-500">IDR {{ number_format($vendor['harga']) }}</span>
                                </label>
                            @empty
                                <div class="text-gray-500">Tidak ada vendor untuk kategori ini.</div>
                            @endforelse
                            @if($errors->has('grooms_name'))
                                <span class="text-red-600">{{ $errors->first('grooms_name') }}</span>
                            @endif
                        </div>

                        <div class="flex justify-between">
                            <button wire:click="prevStep" class="btn btn-gray" @if($vendor_step === 0) disabled
                            @endif>Kembali</button>
                            @if($vendor_step < $categoryCount - 1)
                                <button wire:click="nextStep" class="btn btn-teal">Berikutnya</button>
                            @else
                                <button wire:click="nextStep" class="btn btn-teal">Selesai</button>
                            @endif
                        </div>
                    @else
                        <div class="text-center text-gray-500">Tidak ada vendor yang tersedia untuk venue ini.</div>
                        <div class="mt-4">
                            <button wire:click="nextStep" class="btn btn-teal">Lewati</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tailwind input/button classes (add in your global CSS if you like) --}}
        <style>
            .input {
                @apply w-full border rounded-lg px-3 py-2 mb-2 focus:outline-none focus:ring-2 focus:ring-teal-400;
            }

            .btn {
                @apply px-5 py-2 rounded-lg font-semibold shadow hover:shadow-md;
            }

            .btn-teal {
                @apply bg-teal-600 text-white hover:bg-teal-700;
            }

            .btn-gray {
                @apply bg-gray-200 text-gray-700 hover:bg-gray-300;
            }
        </style>
    </div>
</x-app-layout>