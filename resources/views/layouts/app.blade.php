<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lavanya Wedding Organizer')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS (dev via CDN; use compiled CSS for production) -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Google Fonts: Cinzel -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">

    <!-- Axios for AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        html,
        body {
            font-family: 'Cinzel', serif;
            min-height: 100vh;
            /* Use only the wallpaper image */
            background: url('/images/bg.png') no-repeat center center fixed;
            background-size: cover;
        }

        @media (max-width: 640px) {

            html,
            body {
                background: none !important;
                /* Remove bg from main body */
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
                background: url('/images/bg.png') no-repeat center center;
                background-size: cover;
                transform: rotate(90deg);
                width: 100vw;
                height: 100vh;
                display: block;
            }
        }

        .form-section {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            /* height: 100%; */ /* REMOVED to allow dynamic height */
            background: white;
            /* Ensure sections have a background to cover content below */
            border-radius: 0.5rem;
            /* Re-apply from removed bg-white rounded-lg */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            /* Re-apply from removed shadow-lg */
            padding: 1.5rem;
            /* Re-apply from removed p-6 */
            transform: translateX(100%);
            opacity: 0;
            visibility: hidden;
            transition: transform 0.6s ease-in-out, opacity 0.6s ease-in-out;
            z-index: 1;
        }

        .form-section.left-out {
            transform: translateX(-100%);
        }

        .form-section.active {
            transform: translateX(0);
            opacity: 1;
            visibility: visible;
            z-index: 10;
            /* Active step is on top */
        }

        .form-section.previous {
            transform: translateX(0);
            /* Keep previous step in place */
            opacity: 1;
            visibility: visible;
            z-index: 5;
            /* Behind the active step */
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