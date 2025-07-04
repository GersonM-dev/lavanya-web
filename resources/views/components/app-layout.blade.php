<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Wedding Order Form' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CSS via CDN (for quick prototyping; use Laravel Mix or Vite in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    @livewireStyles
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen font-sans antialiased">

    <main class="container mx-auto px-4">
        {{ $slot ?? '' }}

    </main>

    @livewireScripts
    @stack('scripts')
</body>

</html>