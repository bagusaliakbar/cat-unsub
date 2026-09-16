<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CAT Universitas Subang') }} - Login</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 selection:bg-blue-500 selection:text-white">
        <div class="min-h-screen flex">
            <!-- Left Side - Branding (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-[55%] bg-gradient-to-br from-blue-900 via-blue-800 to-blue-600 flex-col justify-between p-12 text-white relative overflow-hidden">
                <!-- Background decorations -->
                <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 p-2 rounded-xl backdrop-blur-md border border-white/10">
                            <x-application-logo class="w-10 h-10" />
                        </div>
                        <span class="text-xl font-bold tracking-wider">CAT UNSUB</span>
                    </div>
                </div>

                <div class="relative z-10 mt-auto mb-auto">
                    <h1 class="text-5xl font-extrabold tracking-tight mb-6 leading-tight">
                        Computer Assisted Test<br>
                        <span class="text-blue-200">Universitas Subang</span>
                    </h1>
                    <p class="text-xl text-blue-100 max-w-lg font-medium leading-relaxed">
                        Sistem Ujian Online yang Mengedepankan Kejujuran, Transparansi, dan Akuntabilitas.
                    </p>
                    
                    <div class="mt-10 flex items-center space-x-4 text-sm font-medium text-blue-200">
                        <div class="flex items-center bg-blue-800/50 rounded-full px-4 py-2 backdrop-blur-sm border border-blue-700/50">
                            <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Real-time
                        </div>
                        <div class="flex items-center bg-blue-800/50 rounded-full px-4 py-2 backdrop-blur-sm border border-blue-700/50">
                            <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Akurat
                        </div>
                        <div class="flex items-center bg-blue-800/50 rounded-full px-4 py-2 backdrop-blur-sm border border-blue-700/50">
                            <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Aman
                        </div>
                    </div>
                </div>

                <div class="relative z-10 text-sm text-blue-200/80 font-medium">
                    &copy; {{ date('Y') }} Universitas Subang. All rights reserved.
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="w-full lg:w-[45%] flex items-center justify-center p-8 sm:p-12 relative bg-white">
                
                <!-- Mobile Branding (Shows only on mobile) -->
                <div class="absolute top-8 left-8 lg:hidden flex items-center text-blue-700 font-bold">
                    <x-application-logo class="w-10 h-10 mr-2" />
                    CAT UNSUB
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
