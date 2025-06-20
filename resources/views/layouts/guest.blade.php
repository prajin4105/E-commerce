<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary-color: #3b82f6;
                --primary-hover: #2563eb;
                --text-primary: #1f2937;
                --text-secondary: #6b7280;
                --bg-primary: #ffffff;
                --bg-secondary: #f3f4f6;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                color: var(--text-primary);
                line-height: 1.5;
                background: linear-gradient(135deg, var(--bg-secondary) 0%, #e5e7eb 100%);
            }

            .min-h-screen {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 2rem 1rem;
            }

            .logo-container {
                margin-bottom: 2rem;
            }

            .logo-container a {
                display: inline-block;
                transition: transform 0.2s ease;
            }

            .logo-container a:hover {
                transform: scale(1.05);
            }

            .content-container {
                width: 100%;
                max-width: 28rem;
                background: var(--bg-primary);
                border-radius: 1rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                padding: 2rem;
            }

            @media (max-width: 640px) {
                .min-h-screen {
                    padding: 1rem;
                }

                .content-container {
                    padding: 1.5rem;
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen">
            <div class="content-container">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
