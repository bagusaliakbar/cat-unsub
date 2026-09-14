<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Peserta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session()->has('message'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm mb-6 font-bold" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Daftar Ujian Tersedia
                </h3>
                
                @php
                    $availableExams = \App\Models\Exam::where('is_active', true)->get();
                @endphp

                @if($availableExams->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($availableExams as $exam)
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow group flex flex-col justify-between">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-blue-600 transition-colors">{{ $exam->title }}</h4>
                                    <p class="text-sm text-gray-600 line-clamp-2 mb-4">{{ $exam->description ?? 'Ujian seleksi/evaluasi.' }}</p>
                                    
                                    <div class="space-y-2 mb-6">
                                        <div class="flex items-center text-sm font-medium text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Durasi: {{ $exam->duration_minutes }} Menit
                                        </div>
                                        <div class="flex items-center text-sm font-medium text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            Batas Lulus: {{ $exam->passing_grade }}
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="{{ route('participant.exam', $exam->id) }}" class="block w-full text-center bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition shadow-sm">
                                    Mulai Ujian Sekarang
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-gray-500 text-lg">Belum ada ujian yang dijadwalkan untuk Anda saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
