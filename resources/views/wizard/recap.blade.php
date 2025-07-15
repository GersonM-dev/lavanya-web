@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
@endpush

@section('content')
<div class="w-full max-w-2xl mx-auto py-12 px-2">
    <div class="bg-white rounded-2xl shadow-2xl p-8 border border-amber-100"
         style="min-height: 700px"
         data-aos="fade-in">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-amber-800 mb-2 tracking-tight">Recap Wedding Plan</h1>
            <p class="text-base text-gray-500">Lavanya Java Heritage</p>
            <p class="text-md text-amber-600 mt-2">Terima kasih telah mempercayakan momen spesial Anda kepada kami.</p>
        </div>

        <!-- Body Content -->
        <div class="space-y-7">
            <!-- Customer Info -->
            <div>
                <div class="text-lg font-bold text-amber-700 mb-2 flex items-center gap-2 border-t border-amber-400 pt-4">
                    Data Pengantin
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-8 text-sm">
                    <div><span class="font-semibold">Pengantin Pria:</span> <span class="text-gray-700">{{ $customer->grooms_name ?? '-' }}</span></div>
                    <div><span class="font-semibold">Pengantin Wanita:</span> <span class="text-gray-700">{{ $customer->brides_name ?? '-' }}</span></div>
                    <div>
                        <span class="font-semibold">Jumlah Tamu:</span>
                        <span class="text-gray-700">
                            {{ $customer->guest_count ? $customer->guest_count . ' orang' : '-' }}
                        </span>
                    </div>
                    <div><span class="font-semibold">Tanggal Pernikahan:</span> <span class="text-gray-700">{{ \Carbon\Carbon::parse($customer->wedding_date ?? now())->isoFormat('D MMMM Y') }}</span></div>
                    <div><span class="font-semibold">Nomor Telepon:</span> <span class="text-gray-700">{{ $customer->phone_number ?? '-' }}</span></div>
                    @if(!empty($customer->referral_code))
                        <div><span class="font-semibold">Kode Referral:</span> <span class="text-amber-700">{{ $customer->referral_code }}</span></div>
                    @endif
                </div>
            </div>

            <!-- Venue Info -->
            <div>
                <div class="text-lg font-bold text-amber-700 mb-2 flex items-center gap-2 border-t border-amber-400 pt-4">
                    Venue
                </div>
                <div class="overflow-x-auto rounded border border-amber-300">
                    <table class="min-w-full table-auto bg-white">
                        <thead class="bg-amber-100 text-left">
                            <tr>
                                @if($venue->gambar ?? false)
                                    <th class="p-3 font-semibold text-amber-700">Gambar</th>
                                @endif
                                <th class="p-3 font-semibold text-amber-700">Nama</th>
                                <th class="p-3 font-semibold text-amber-700">Tipe</th>
                                @if($venue->kapasitas ?? false)
                                    <th class="p-3 font-semibold text-amber-700">Kapasitas</th>
                                @endif
                                <th class="p-3 font-semibold text-amber-700">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t">
                                @if($venue->gambar ?? false)
                                    <td class="p-3">
                                        <img src="{{ $venue->gambar }}" alt="Venue"
                                            class="w-40 h-28 object-cover rounded-lg border border-amber-100">
                                    </td>
                                @endif
                                <td class="p-3">{{ $venue->nama ?? '-' }}</td>
                                <td class="p-3">{{ $venue->type ?? '-' }}</td>
                                @if($venue->kapasitas ?? false)
                                    <td class="p-3">{{ $venue->kapasitas }}</td>
                                @endif
                                <td class="p-3">{!! str($venue->deskripsi ?? '-')->sanitizeHtml() !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Catering Info -->
            @if($catering)
            <div>
                <div class="text-lg font-bold text-amber-700 mb-2 flex items-center gap-2 border-t border-amber-400 pt-4">
                    Catering
                </div>
                <div class="overflow-x-auto rounded border border-amber-300">
                    <table class="min-w-full table-auto bg-white">
                        <thead class="bg-amber-100 text-left">
                            <tr>
                                <th class="p-3 font-semibold text-amber-700">Nama</th>
                                <th class="p-3 font-semibold text-amber-700">Tipe</th>
                                <th class="p-3 font-semibold text-amber-700">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t">
                                <td class="p-3">{{ $catering->nama ?? '-' }}</td>
                                <td class="p-3">{{ $catering->type ?? '-' }}</td>
                                <td class="p-3">{!! str($catering->deskripsi ?? '-')->sanitizeHtml() !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Vendors Table -->
            <div>
                <div class="text-lg font-bold text-amber-700 mb-2 flex items-center gap-2 border-t border-amber-400 pt-4">
                    Vendors
                </div>
                <div class="overflow-x-auto rounded border border-amber-300">
                    <table class="min-w-full table-auto bg-white">
                        <thead class="bg-amber-100 text-left">
                            <tr>
                                <th class="p-3 font-semibold text-amber-700">Nama</th>
                                <th class="p-3 font-semibold text-amber-700">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendors as $v)
                            <tr class="border-t">
                                <td class="p-3">{{ $v->vendor->nama ?? '-' }}</td>
                                <td class="p-3">{!! str($v->vendor->deskripsi ?? '-')->sanitizeHtml() !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total Budget -->
            <div class="pt-7 mt-5 border-t border-amber-100 text-center">
                <h2 class="text-xl font-extrabold text-amber-800 mb-2 tracking-tight">Total Budget</h2>
                <div class="text-3xl text-amber-600 font-bold mb-2">IDR {{ number_format($total, 0, ',', '.') }}</div>
                <a href="{{ route('wizard.recap.pdf', ['transaction' => $transaction->id]) }}"
                    target="_blank"
                    class="inline-block mt-2 bg-amber-600 text-white px-5 py-2 rounded-xl shadow hover:bg-amber-700 transition">
                    Download PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, duration: 700 });
    </script>
@endpush
