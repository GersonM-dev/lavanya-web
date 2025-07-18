@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
@endpush

@section('content')
    <div class="w-full max-w-4xl mx-auto py-12 px-8">
        <div class="bg-white rounded-2xl shadow-2xl p-12 border border-amber-100"
            style="min-height: 700px; width: 800px; margin: 0 auto;" data-aos="fade-in">
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
                    <div
                        class="text-lg font-bold text-amber-700 mb-2 flex items-center gap-2 border-t border-amber-400 pt-4">
                        Data Pengantin
                    </div>
                    <div class="grid grid-cols-2 gap-y-2 gap-x-8 text-sm">
                        <div><span class="font-semibold">Pengantin Pria:</span> <span
                                class="text-gray-700">{{ $customer->grooms_name ?? '-' }}</span></div>
                        <div><span class="font-semibold">Pengantin Wanita:</span> <span
                                class="text-gray-700">{{ $customer->brides_name ?? '-' }}</span></div>
                        <div>
                            <span class="font-semibold">Jumlah Tamu:</span>
                            <span class="text-gray-700">
                                {{ $customer->guest_count ? $customer->guest_count . ' orang' : '-' }}
                            </span>
                        </div>
                        <div><span class="font-semibold">Tanggal Pernikahan:</span> <span
                                class="text-gray-700">{{ \Carbon\Carbon::parse($customer->wedding_date ?? now())->isoFormat('D MMMM Y') }}</span>
                        </div>
                        <div><span class="font-semibold">Nomor Telepon:</span> <span
                                class="text-gray-700">{{ $customer->phone_number ?? '-' }}</span></div>
                        @if(!empty($customer->referral_code))
                            <div><span class="font-semibold">Kode Referral:</span> <span
                                    class="text-amber-700">{{ $customer->referral_code }}</span></div>
                        @endif
                    </div>
                </div>

                <!-- Venue Info -->
                <div>
                    <div
                        class="text-lg font-bold text-amber-700 mb-2 flex items-center gap-2 border-t border-amber-400 pt-4">
                        Venue
                    </div>
                    <div class="rounded border border-amber-300" style="width: 100%;">
                        <table class="w-full table-fixed bg-white">
                            <thead class="bg-amber-100 text-left">
                                <tr>
                                    @if($venue->gambar ?? false)
                                        <th class="p-3 font-semibold text-amber-700" style="width: 200px;">Gambar</th>
                                    @endif
                                    <th class="p-3 font-semibold text-amber-700" style="width: 150px;">Nama</th>
                                    <th class="p-3 font-semibold text-amber-700" style="width: 100px;">Tipe</th>
                                    @if($venue->kapasitas ?? false)
                                        <th class="p-3 font-semibold text-amber-700" style="width: 100px;">Kapasitas</th>
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
                        <div
                            class="text-lg font-bold text-amber-700 mb-2 flex items-center gap-2 border-t border-amber-400 pt-4">
                            Catering
                        </div>
                        <div class="rounded border border-amber-300" style="width: 100%;">
                            <table class="w-full table-fixed bg-white">
                                <thead class="bg-amber-100 text-left">
                                    <tr>
                                        <th class="p-3 font-semibold text-amber-700" style="width: 200px;">Nama</th>
                                        <th class="p-3 font-semibold text-amber-700" style="width: 150px;">Tipe</th>
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
                    <div
                        class="text-lg font-bold text-amber-700 mb-2 flex items-center gap-2 border-t border-amber-400 pt-4">
                        Vendors
                    </div>
                    <div class="rounded border border-amber-300" style="width: 100%;">
                        <table class="w-full table-fixed bg-white">
                            <thead class="bg-amber-100 text-left">
                                <tr>
                                    <th class="p-3 font-semibold text-amber-700" style="width: 250px;">Nama</th>
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
                    @if(isset($total_before_discount) && $total_before_discount > 0 && $total_before_discount != $total)
                        <div class="text-lg text-gray-400 font-semibold mb-0.5">
                            <span class="line-through">
                                IDR {{ number_format($total_before_discount, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="text-3xl text-amber-600 font-bold mb-2">
                            IDR {{ number_format($total, 0, ',', '.') }}
                        </div>
                    @else
                        <div class="text-3xl text-amber-600 font-bold mb-2">
                            IDR {{ number_format($total, 0, ',', '.') }}
                        </div>
                    @endif
                    <a href="{{ route('wizard.recap.pdf', ['transaction' => $transaction->id]) }}" target="_blank"
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