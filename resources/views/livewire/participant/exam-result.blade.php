<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hasil Ujian: {{ $exam->title }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 p-8 md:p-16 text-center transform transition-all">
                
                <!-- Icon -->
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 mb-8">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>

                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Ujian Selesai!</h1>
                <p class="text-lg text-gray-500 mb-8 max-w-xl mx-auto">
                    Terima kasih telah mengikuti ujian <strong>{{ $exam->title }}</strong>. Berikut adalah hasil pencapaian Anda.
                </p>

                <div class="bg-blue-50/50 border border-blue-100 rounded-3xl p-8 max-w-md mx-auto mb-10 shadow-sm">
                    <h3 class="text-sm font-bold text-blue-800 uppercase tracking-widest mb-2">Total Skor Anda</h3>
                    <div class="text-6xl font-black text-blue-600">
                        {{ round($session->score) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
