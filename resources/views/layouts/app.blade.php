<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lavanya Wedding Organizer')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS (dev via CDN; use compiled CSS for production) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Cinzel -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">

    <!-- Axios for AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        html,
        body {
            font-family: 'Cinzel', serif;
            background: linear-gradient(135deg, #fef9c3 0%, #fafaf9 100%);
            min-height: 100vh;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        #detail-modal.show {
            opacity: 1;
            transform: scale(1);
            transition: all 0.25s cubic-bezier(.4, 2, .6, 1);
        }

        #detail-modal-overlay.show {
            display: flex !important;
        }

        #detail-modal-overlay {
            background: transparent !important;
            align-items: flex-start !important;
        }

        #detail-modal {
            margin-top: 0;
            /* reset if needed */
        }
    </style>

    @stack('head')
</head>

<body class="min-h-screen flex flex-col">
    <main class="flex-1 flex flex-col justify-center items-center py-10">
        @yield('content')
    </main>
    <footer class="text-center text-sm py-4 text-gray-400 border-t border-gray-100 bg-white">
        &copy; {{ date('Y') }} Lavanya Wedding Organizer
    </footer>

    @stack('scripts')
</body>

</html>