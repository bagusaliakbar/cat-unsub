<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Riwayat Ujian
    </h2>
</x-slot>

<div class="w-full py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center text-sm text-gray-600 font-medium space-x-2 mb-6 px-2 sm:px-0">
        <span class="text-blue-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Peserta
        </span>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 font-bold text-base">Riwayat Ujian</span>
    </div>

    <!-- Premium Header -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-600 rounded-2xl shadow-lg p-6 mb-8 text-white flex flex-col md:flex-row items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight mb-1 flex items-center">
                <svg class="w-8 h-8 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Ujian
            </h2>
            <p class="text-blue-100 opacity-90 text-sm">Lihat kembali nilai dan hasil ujian yang pernah Anda kerjakan.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                        <th class="p-4 pl-6">Ujian</th>
                        <th class="p-4">Tanggal Dikerjakan</th>
                        <th class="p-4 text-center">Nilai Akhir</th>
                        <th class="p-4 text-right pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 pl-6">
                                <div class="font-bold text-gray-900">{{ $session->exam->title }}</div>
                            </td>
                            <td class="p-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($session->completed_at)->translatedFormat('d F Y, H:i') }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full font-bold text-sm bg-blue-100 text-blue-800">
                                    {{ $session->score }}
                                </span>
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <a href="{{ route('participant.exam.result', $session->exam_id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-blue-600 hover:bg-gray-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Lihat Hasil
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="font-medium">Anda belum menyelesaikan ujian apapun.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sessions->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $sessions->links() }}
            </div>
        @endif
    </div>
</div>
