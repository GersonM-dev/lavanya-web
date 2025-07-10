<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Wedding Plan Recap</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-amber-50 min-h-screen flex items-center">

    <div class="w-full max-w-2xl mx-auto space-y-7 py-12 px-2">
        <!-- Header Hero Card -->
        <div class="bg-white rounded-3xl shadow-xl py-7 px-6 text-center relative overflow-hidden mb-6 border-2 border-indigo-200">
            <div class="mb-2">
                <svg class="w-12 h-12 mx-auto mb-2 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7 17l5-5 5 5M7 7l5 5 5-5"/>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-indigo-700 mb-1 tracking-tight">Recap Wedding Plan</h1>
            <p class="text-base text-gray-500 mb-2">Lavanya Java Heritage</p>
            <p class="text-xs text-amber-600">Terima kasih telah mempercayakan momen spesial Anda kepada kami.</p>
        </div>

        <!-- Customer Info -->
        <div class="bg-white rounded-2xl shadow-md p-6 flex flex-col gap-2 border border-indigo-100">
            <h2 class="text-lg font-bold text-indigo-600 mb-2 tracking-tight flex items-center gap-2">
                <span class="material-icons align-bottom text-amber-500">favorite</span>
                Data Pengantin
            </h2>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div><span class="font-semibold">Pengantin Pria:</span> <span class="text-gray-700">{{ $customer->grooms_name }}</span></div>
                <div><span class="font-semibold">Pengantin Wanita:</span> <span class="text-gray-700">{{ $customer->brides_name }}</span></div>
                <div><span class="font-semibold">Tamu:</span> <span class="text-gray-700">{{ $customer->guest_count }}</span></div>
                <div><span class="font-semibold">Tanggal:</span> <span class="text-gray-700">{{ $customer->wedding_date }}</span></div>
                <div><span class="font-semibold">Telepon:</span> <span class="text-gray-700">{{ $customer->phone_number }}</span></div>
                @if(isset($customer->referral_code))
                <div><span class="font-semibold">Kode Referral:</span> <span class="text-amber-700">{{ $customer->referral_code }}</span></div>
                @endif
            </div>
        </div>

        <!-- Venue Card -->
        <div class="bg-gradient-to-r from-indigo-100 via-white to-amber-100 rounded-2xl shadow p-6 border border-indigo-50">
            <h2 class="text-lg font-bold text-amber-700 mb-3 tracking-tight flex items-center gap-2">
                <span class="material-icons align-bottom text-indigo-500">place</span>
                Venue
            </h2>
            <div class="text-sm space-y-2">
                <div><span class="font-semibold">Nama:</span> {{ $venue->nama ?? '-' }}</div>
                <div><span class="font-semibold">Tipe:</span> {{ $venue->type ?? '-' }}</div>
                <div><span class="font-semibold">Deskripsi:</span> {!! str($venue->deskripsi ?? '-')->sanitizeHtml() !!}</div>
            </div>
        </div>

        <!-- Catering Card -->
        @if($catering)
        <div class="bg-white rounded-2xl shadow p-6 border border-indigo-50">
            <h2 class="text-lg font-bold text-pink-700 mb-3 tracking-tight flex items-center gap-2">
                <span class="material-icons align-bottom text-amber-500">restaurant</span>
                Catering
            </h2>
            <div class="text-sm space-y-2">
                <div><span class="font-semibold">Nama:</span> {{ $catering->nama ?? '-' }}</div>
                <div><span class="font-semibold">Deskripsi:</span> {!! str($catering->deskripsi ?? '-')->sanitizeHtml() !!}</div>
            </div>
        </div>
        @endif

        <!-- Vendors Card -->
        <div class="bg-white rounded-2xl shadow p-6 border border-indigo-50">
            <h2 class="text-lg font-bold text-green-700 mb-3 tracking-tight flex items-center gap-2">
                <span class="material-icons align-bottom text-indigo-500">people</span>
                Vendors
            </h2>
            <div class="overflow-x-auto rounded border border-gray-100">
                <table class="min-w-full table-auto bg-white">
                    <thead class="bg-indigo-50 text-left">
                        <tr>
                            <th class="p-3 font-semibold text-indigo-700">Nama</th>
                            <th class="p-3 font-semibold text-indigo-700">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendors as $vendor)
                        <tr class="border-t">
                            <td class="p-3">{{ $vendor->vendor->nama ?? '-' }}</td>
                            <td class="p-3">{!! str($vendor->vendor->deskripsi ?? '-')->sanitizeHtml() !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Budget Highlight Card -->
        <div class="bg-gradient-to-r from-amber-100 via-white to-indigo-100 shadow rounded-2xl py-5 px-6 text-center border-2 border-amber-200">
            <h2 class="text-xl font-extrabold text-indigo-800 mb-1 tracking-tight">Total Budget</h2>
            <div class="text-3xl text-amber-600 font-bold">IDR {{ number_format($total, 0, ',', '.') }}</div>
        </div>
    </div>
    <!-- If you want to use material icons (uncomment if needed) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</body>
</html>
