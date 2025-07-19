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
            /* Add wallpaper as a centered, covered background */
            background-image: linear-gradient(135deg, #fef9c3 0%, #fafaf9 100%), url('/bg.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        @media (max-width: 640px) {

            html,
            body {
                /* On mobile, rotate the bg image 90deg (clockwise) using a pseudo element */
                background-image: linear-gradient(135deg, #fef9c3 0%, #fafaf9 100%);
                position: relative;
            }

            body::before {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: -1;
                background: url('/bg.png') no-repeat center center;
                background-size: cover;
                transform: rotate(90deg);
                /* Ensure image covers whole body even after rotate */
                width: 100vw;
                height: 100vh;
                display: block;
            }
        }

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
    <main>
        @yield('content')
    </main>

    <footer class="text-center text-sm py-4 text-gray-400 border-t border-gray-100 bg-white">
        &copy; {{ date('Y') }} Lavanya Wedding Organizer
    </footer>

    @stack('scripts')
</body>

</html>