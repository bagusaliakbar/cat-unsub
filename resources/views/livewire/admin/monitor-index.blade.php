<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Monitor Ujian
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-10">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <div class="flex items-center text-sm text-gray-600 font-medium space-x-2 mb-6">
                <span class="text-blue-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Admin
                </span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-900 font-bold text-base">Monitor Ujian</span>
            </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($exams as $exam)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col transform transition duration-300 hover:shadow-xl hover:-translate-y-1">
                    
                    <div class="p-8 flex-grow">
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-xl font-bold text-gray-900 leading-tight">{{ $exam->title }}</h3>
                            @if($exam->active_sessions_count > 0)
                                <span class="inline-flex items-center text-xs font-bold bg-green-50 text-green-700 px-3 py-1.5 rounded-full border border-green-200 shadow-sm whitespace-nowrap ml-4">
                                    <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center text-xs font-medium bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full whitespace-nowrap ml-4">
                                    Tidak ada sesi
                                </span>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
                            <!-- Total Peserta -->
                            <div class="bg-gray-50 rounded-2xl p-2.5 sm:p-3 text-center border border-gray-100 flex flex-col items-center justify-center">
                                <svg class="w-5 h-5 mx-auto text-gray-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <div class="text-[10px] sm:text-[11px] text-gray-500 font-medium">Total Peserta</div>
                                <div class="text-sm sm:text-base font-bold text-gray-900">{{ $exam->participants_count }}</div>
                            </div>

                            <!-- Sedang Ujian -->
                            <div class="bg-blue-50/50 rounded-2xl p-2.5 sm:p-3 text-center border border-blue-100 flex flex-col items-center justify-center">
                                <svg class="w-5 h-5 mx-auto text-blue-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <div class="text-[10px] sm:text-[11px] text-blue-700 font-medium">Sedang Ujian</div>
                                <div class="text-sm sm:text-base font-bold text-blue-900">{{ $exam->active_sessions_count }}</div>
                            </div>
                            
                            <!-- Selesai -->
                            <div class="bg-green-50/50 rounded-2xl p-2.5 sm:p-3 text-center border border-green-100 flex flex-col items-center justify-center">
                                <svg class="w-5 h-5 mx-auto text-green-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div class="text-[10px] sm:text-[11px] text-green-700 font-medium">Selesai</div>
                                <div class="text-sm sm:text-base font-bold text-green-900">{{ $exam->completed_sessions_count }}</div>
                            </div>
                            
                            <!-- Durasi -->
                            <div class="bg-gray-50 rounded-2xl p-2.5 sm:p-3 text-center border border-gray-100 flex flex-col items-center justify-center">
                                <svg class="w-5 h-5 mx-auto text-gray-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div class="text-[10px] sm:text-[11px] text-gray-500 font-medium">Durasi</div>
                                <div class="text-sm sm:text-base font-bold text-gray-900">{{ $exam->duration_minutes ? $exam->duration_minutes . ' Mnt' : '-' }}</div>
                            </div>
                            
                            <!-- Tanggal & Waktu -->
                            <div class="bg-gray-50 rounded-2xl p-2.5 sm:p-3 text-center border border-gray-100 flex flex-col items-center justify-center">
                                <svg class="w-5 h-5 mx-auto text-gray-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div class="text-[10px] sm:text-[11px] text-gray-500 font-medium">Jadwal Ujian</div>
                                <div class="text-xs sm:text-sm font-bold text-gray-900 mt-0.5 leading-tight">
                                    @if($exam->start_time)
                                        {{ \Carbon\Carbon::parse($exam->start_time)->format('d M y') }}<br>
                                        <span class="text-[10px] text-gray-500 font-normal">{{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}</span>
                                    @else
                                        Kapan saja
                                    @endif
                                </div>
                            </div>

                            <!-- Ruangan / Gelombang -->
                            <div class="bg-gray-50 rounded-2xl p-2.5 sm:p-3 text-center border border-gray-100 flex flex-col items-center justify-center">
                                <svg class="w-5 h-5 mx-auto text-gray-500 mb-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <div class="text-[10px] sm:text-[11px] text-gray-500 font-medium shrink-0">Ruangan</div>
                                <div class="text-xs sm:text-sm font-bold text-gray-900 mt-0.5 leading-tight line-clamp-2" title="{{ $exam->location ?: 'Tidak ditentukan' }}">
                                    {{ $exam->location ?: 'Tidak ditentukan' }}
                                </div>
                                <div class="text-[9px] text-gray-500 font-normal mt-0.5 shrink-0 truncate max-w-full" title="{{ $exam->wave->name ?? '-' }}">
                                    {{ $exam->wave->name ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('admin.exams.monitor', $exam->id) }}" wire:navigate class="w-full flex justify-center items-center px-4 py-3 bg-gradient-to-r from-blue-700 to-blue-800 hover:from-blue-800 hover:to-blue-900 text-white rounded-xl transition shadow-md hover:shadow-lg font-bold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Buka Layar Monitor
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl shadow-sm p-12 text-center border border-gray-100 max-w-2xl mx-auto transform transition duration-500 hover:shadow-md hover:-translate-y-1">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Ujian</h3>
                    <p class="text-gray-500">Tidak ada ujian yang sedang aktif saat ini untuk dimonitor.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
