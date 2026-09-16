<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- ApexCharts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @if (!request()->routeIs('participant.exam.execute'))
                <livewire:layout.navigation />
            @endif

            <!-- Page Content -->
            <div class="{{ !request()->routeIs('participant.exam.execute') ? 'md:pl-64' : '' }} flex flex-col min-h-screen transition-all duration-300">
                @if (isset($header))
                    <header class="bg-white shadow z-10 sticky top-0">
                        <div class="w-full py-3 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                            <div class="flex-1">
                                {{ $header }}
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <livewire:layout.top-header />
                            </div>
                        </div>
                    </header>
                @else
                    <header class="bg-white shadow z-10 sticky top-0">
                        <div class="w-full py-3 px-4 sm:px-6 lg:px-8 flex justify-end items-center">
                            <div class="flex-shrink-0">
                                <livewire:layout.top-header />
                            </div>
                        </div>
                    </header>
                @endif

                <main class="flex-1 w-full">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
