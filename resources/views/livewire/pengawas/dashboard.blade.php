<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Pengawas
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-10">
        <div class="w-full px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            
            <!-- Hero Header -->
            <div class="bg-gradient-to-r from-blue-700 via-indigo-800 to-purple-900 rounded-3xl shadow-xl p-8 mb-10 text-white relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-white opacity-10 blur-3xl"></div>
                <div class="absolute bottom-0 right-20 w-32 h-32 rounded-full bg-blue-400 opacity-20 blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold mb-2 tracking-tight text-white">Selamat Bertugas, {{ auth()->user()->name }}!</h1>
                        <p class="text-blue-100 text-lg max-w-2xl">Pusat pemantauan ujian real-time. Pastikan integritas dan kelancaran pelaksanaan ujian hari ini.</p>
                    </div>
                    <div class="mt-6 md:mt-0 text-right">
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-6 py-4 text-center">
                            <div class="text-xs text-blue-200 font-bold uppercase tracking-wider mb-1">Waktu Sistem</div>
                            <div class="text-2xl font-bold font-mono" x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }" x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }), 1000)" x-text="time"></div>
                            <div class="text-sm text-blue-100 mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $liveExams = $exams->where('active_sessions_count', '>', 0);
                $otherExams = $exams->where('active_sessions_count', '==', 0);
            @endphp

            @if($liveExams->count() > 0)
                <div class="mb-10">
                    <div class="flex items-center mb-6">
                        <div class="w-3 h-3 bg-red-500 rounded-full animate-ping mr-3"></div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">SEDANG BERLANGSUNG</h3>
                        <span class="ml-4 bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full border border-red-200 shadow-sm">{{ $liveExams->count() }} Ujian Aktif</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($liveExams as $exam)
                            <div class="bg-white rounded-3xl shadow-lg border border-red-100 overflow-hidden flex flex-col transform transition duration-300 hover:shadow-2xl hover:-translate-y-1 relative">
                                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-orange-500"></div>
                                
                                <div class="p-8 flex-grow">
                                    <div class="flex justify-between items-start mb-6">
                                        <h3 class="text-2xl font-bold text-gray-900 leading-tight pr-4">{{ $exam->title }}</h3>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-8">
                                        <div class="bg-red-50/50 rounded-2xl p-4 text-center border border-red-100 flex flex-col items-center justify-center relative overflow-hidden">
                                            <div class="absolute -right-4 -bottom-4 opacity-5">
                                                <svg class="w-24 h-24 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                                            </div>
                                            <div class="text-xs text-red-700 font-bold uppercase tracking-wider mb-1">Peserta Aktif</div>
                                            <div class="text-4xl font-black text-red-900">{{ $exam->active_sessions_count }}<span class="text-lg text-red-600 font-medium ml-1">/ {{ $exam->participants_count }}</span></div>
                                        </div>

                                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 flex flex-col justify-center">
                                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                <span class="font-medium truncate" title="{{ $exam->location ?: 'Tidak ditentukan' }}">{{ $exam->location ?: 'Tidak ditentukan' }}</span>
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span class="font-medium">{{ $exam->duration_minutes ? $exam->duration_minutes . ' Menit' : '-' }}</span>
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span class="font-medium">{{ $exam->completed_sessions_count }} Selesai</span>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('pengawas.exams.monitor', $exam->id) }}" wire:navigate class="w-full flex justify-center items-center px-4 py-4 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-xl transition shadow-lg hover:shadow-xl font-bold text-lg">
                                        <svg class="w-6 h-6 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Buka Ruang Monitor
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($otherExams->count() > 0)
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Daftar Ujian Lainnya
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($otherExams as $exam)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col transform transition duration-300 hover:shadow-md">
                                <div class="p-6 flex-grow flex flex-col">
                                    <h4 class="text-lg font-bold text-gray-900 leading-tight mb-4 flex-grow">{{ $exam->title }}</h4>
                                    
                                    <div class="grid grid-cols-2 gap-3 mb-5">
                                        <div class="bg-gray-50 rounded-xl p-2 text-center border border-gray-100">
                                            <div class="text-[10px] text-gray-500 font-medium">Total Peserta</div>
                                            <div class="text-sm font-bold text-gray-900">{{ $exam->participants_count }}</div>
                                        </div>
                                        <div class="bg-green-50/50 rounded-xl p-2 text-center border border-green-100">
                                            <div class="text-[10px] text-green-700 font-medium">Selesai</div>
                                            <div class="text-sm font-bold text-green-900">{{ $exam->completed_sessions_count }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-xs text-gray-500 mb-4 space-y-1.5">
                                        <div class="flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            @if($exam->start_time)
                                                {{ \Carbon\Carbon::parse($exam->start_time)->format('d M y, H:i') }}
                                            @else
                                                Kapan saja
                                            @endif
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            <span class="truncate">{{ $exam->location ?: 'Tidak ditentukan' }}</span>
                                        </div>
                                    </div>

                                    <a href="{{ route('pengawas.exams.monitor', $exam->id) }}" wire:navigate class="w-full flex justify-center items-center px-4 py-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700 hover:text-blue-600 rounded-xl transition font-semibold text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat Monitor
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($exams->count() === 0)
                <div class="bg-white rounded-3xl shadow-sm p-12 text-center border border-gray-100 max-w-2xl mx-auto transform transition duration-500 hover:shadow-md hover:-translate-y-1">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Ujian Aktif</h3>
                    <p class="text-gray-500">Tidak ada jadwal ujian yang terbuka saat ini. Hubungi administrator jika Anda merasa ini adalah sebuah kesalahan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
