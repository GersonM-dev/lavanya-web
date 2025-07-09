<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Wedding Plan Recap</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-white text-gray-800 font-sans text-sm px-6 py-8">

    <div class="max-w-3xl mx-auto space-y-8">

        <div>
            <h1 class="text-2xl font-bold text-center mb-2">💍 Wedding Plan Recap</h1>
            <p class="text-center text-gray-500">Lavanya Java Heritage</p>
            <hr class="mt-4 border-gray-300">
        </div>

        <!-- Customer Info -->
        <div>
            <h2 class="text-lg font-semibold border-b pb-1 mb-2">👰 Customer Info</h2>
            <ul class="space-y-1">
                <li><strong>Groom:</strong> {{ $customer->grooms_name }}</li>
                <li><strong>Bride:</strong> {{ $customer->brides_name }}</li>
                <li><strong>Guests:</strong> {{ $customer->guest_count }}</li>
                <li><strong>Date:</strong> {{ $customer->wedding_date }}</li>
                <li><strong>Phone:</strong> {{ $customer->phone_number }}</li>
                @if(isset($customer->referral_code))
                    <li><strong>Referral Code:</strong> {{ $customer->referral_code }}</li>
                @endif
            </ul>
        </div>

        <!-- Venue Info -->
        <div>
            <h2 class="text-lg font-semibold border-b pb-1 mb-2">🏛️ Venue</h2>
            <ul class="space-y-1">
                <li><strong>Name:</strong> {{ $venue->nama ?? '-' }}</li>
                <li><strong>Type:</strong> {{ $venue->type ?? '-' }}</li>
                <li><strong>Description:</strong> {!! str($venue->deskripsi ?? '-')->sanitizeHtml() !!}</li>
            </ul>
        </div>

        <!-- Catering -->
        @if($catering)
        <div>
            <h2 class="text-lg font-semibold border-b pb-1 mb-2">🍽️ Catering</h2>
            <ul class="space-y-1">
                <li><strong>Name:</strong> {{ $catering->nama ?? '-' }}</li>
                <li><strong>Description:</strong> {!! str($catering->deskripsi ?? '-')->sanitizeHtml() !!}</li>
            </ul>
        </div>
        @endif

        <!-- Vendors -->
        <div>
            <h2 class="text-lg font-semibold border-b pb-1 mb-3">🎤 Vendors</h2>
            <div class="overflow-hidden border rounded">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="p-3 font-medium">Name</th>
                            <th class="p-3 font-medium">Description</th>
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

        <!-- Total Budget -->
        <div class="text-right">
            <h2 class="text-xl font-bold text-indigo-600">Total Budget: IDR {{ number_format($total, 0, ',', '.') }}</h2>
        </div>

    </div>

</body>
</html>
