<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Dark theme matching the main site */
            body {
                background-color: #000;
                color: #fff;
            }
            .bg-gray-100 {
                background-color: #000 !important;
            }
            .bg-white {
                background-color: #1a1a1a !important;
                color: #fff !important;
            }
            .text-gray-900 {
                color: #fff !important;
            }
            .text-gray-700 {
                color: rgba(255, 255, 255, 0.9) !important;
            }
            .text-gray-600 {
                color: rgba(255, 255, 255, 0.7) !important;
            }
            .text-gray-500 {
                color: rgba(255, 255, 255, 0.6) !important;
            }
            .border-gray-300 {
                border-color: #333 !important;
            }
            .hover\:text-gray-900:hover {
                color: #D4AF37 !important;
            }
            .focus\:ring-indigo-500:focus {
                --tw-ring-color: #D4AF37 !important;
            }
            .text-indigo-600 {
                color: #D4AF37 !important;
            }
            .bg-indigo-600 {
                background-color: #D4AF37 !important;
            }
            .hover\:bg-indigo-700:hover {
                background-color: #B8941F !important;
            }
            input, textarea, select {
                background-color: #0a0a0a !important;
                color: #fff !important;
                border-color: #333 !important;
            }
            input:focus, textarea:focus, select:focus {
                border-color: #D4AF37 !important;
                --tw-ring-color: #D4AF37 !important;
            }
            a {
                color: #D4AF37;
            }
            a:hover {
                color: #B8941F;
            }
            .shadow-md {
                box-shadow: 0 4px 6px -1px rgba(212, 175, 55, 0.1), 0 2px 4px -1px rgba(212, 175, 55, 0.06);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <div class="w-20 h-20 flex items-center justify-center">
                        <span style="font-size: 2rem; color: #D4AF37;">✟</span>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
