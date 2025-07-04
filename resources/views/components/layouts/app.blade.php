<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Wedding Order Form' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @livewireStyles
    @stack('styles')
</head>
<body>
    <main>
        {{ $slot }}
    </main>
    @livewireScripts
    @stack('scripts')
</body>
</html>
