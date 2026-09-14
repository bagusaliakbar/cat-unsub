<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-gray-900 text-white w-full md:w-64 md:min-h-screen flex flex-col md:fixed md:inset-y-0 z-20">
    <!-- Sidebar Header (Logo & Mobile Toggle) -->
    <div class="flex items-center justify-between h-16 px-4 bg-gray-950">
        <a href="{{ route('dashboard') }}" class="flex items-center text-xl font-bold tracking-wider text-white" wire:navigate>
            <x-application-logo class="block h-8 w-auto fill-current text-white mr-2" />
            CAT UNSUB
        </a>
        
        <!-- Mobile menu button -->
        <div class="flex items-center md:hidden">
            <button @click="open = ! open" class="text-gray-400 hover:text-white focus:outline-none">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Navigation Links -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:flex flex-col flex-1 overflow-y-auto">
        <div class="px-2 py-4 space-y-1">
            <div class="text-xs uppercase text-gray-500 font-semibold px-3 mb-2 mt-4">Menu Utama</div>
            
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('admin.dashboard*') ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition-all' }}">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </div>
                </a>
                <a href="{{ route('admin.exams') }}" wire:navigate class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('admin.exams*') ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition-all' }}">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Manajemen Ujian
                    </div>
                </a>
                <a href="{{ route('admin.questions') }}" wire:navigate class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('admin.questions*') ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition-all' }}">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Bank Soal
                    </div>
                </a>
                <a href="{{ route('admin.monitor') }}" wire:navigate class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('admin.monitor*') || request()->routeIs('admin.exams.monitor') ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition-all' }}">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Monitor Ujian
                    </div>
                </a>
                <a href="{{ route('admin.participants') }}" wire:navigate class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('admin.participants*') ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition-all' }}">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Manajemen Peserta
                    </div>
                </a>
                <a href="{{ route('admin.waves') }}" wire:navigate class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('admin.waves*') ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition-all' }}">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Manajemen Gelombang
                    </div>
                </a>
                <a href="{{ route('admin.activity-log') }}" wire:navigate class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('admin.activity-log*') ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition-all' }}">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Log Aktifitas
                    </div>
                </a>
            @else
                <a href="{{ route('participant.dashboard') }}" wire:navigate class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('participant.dashboard*') ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition-all' }}">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard Ujian
                    </div>
                </a>
                <a href="{{ route('participant.history') }}" wire:navigate class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('participant.history*') ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white transition-all' }}">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat Ujian
                    </div>
                </a>
            @endif
        </div>

        <!-- The user info and logout have been moved to the top header -->
    </div>
</nav>

