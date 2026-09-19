<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Layar Monitor Ujian
        </h2>
    </x-slot>
    <div class="w-full py-10 px-4 sm:px-6 lg:px-8" wire:poll.5s>
    
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl shadow-sm relative flex items-center" role="alert">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="block sm:inline font-medium">{{ session('message') }}</span>
        </div>
    @endif

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
            <div class="flex items-center text-sm text-gray-600 font-medium space-x-2">
                <span class="text-blue-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Admin
                </span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <a href="{{ route('admin.monitor') }}" wire:navigate class="hover:text-blue-600 transition">Monitor Ujian</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-900 font-bold text-base line-clamp-1">{{ $exam->title }}</span>
            </div>
            
            <div class="mt-2 sm:mt-0 flex items-center space-x-3">
                <div class="text-sm text-gray-500 flex items-center bg-green-50 px-3 py-2 rounded-full border border-green-200">
                    <span class="animate-pulse h-2 w-2 bg-green-500 rounded-full inline-block mr-2"></span> 
                    <span class="text-green-700 font-semibold text-xs">Real-time Monitoring Aktif</span>
                </div>
                
                <select wire:model.live="filter_wave" class="block w-full sm:w-48 rounded-full border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 px-4 bg-white">
                    <option value="">Semua Gelombang</option>
                    @foreach($waves as $w)
                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                    @endforeach
                </select>
                
                <a href="{{ route('admin.exams.monitor.print', $exam->id) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-4 focus:ring-indigo-400 font-medium py-1.5 px-4 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center border border-indigo-500 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Hasil
                </a>
                
                <button wire:click="export" class="bg-green-600 hover:bg-green-700 text-white focus:ring-4 focus:ring-green-400 font-medium py-1.5 px-4 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center border border-green-500 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Hasil
                </button>
                <div class="hidden sm:block border-l border-gray-300 h-6 mx-1"></div>
                <button wire:click="pauseAllSessions" wire:confirm="Jeda (Pause) ujian SEMUA peserta yang sedang berjalan?" class="bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-4 focus:ring-yellow-400 font-medium py-1.5 px-3 rounded-full shadow-md transition flex items-center border border-yellow-600 text-sm" title="Pause Semua">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>
                <button wire:click="resumeAllSessions" wire:confirm="Lanjutkan (Resume) ujian SEMUA peserta yang sedang dijeda?" class="bg-blue-500 hover:bg-blue-600 text-white focus:ring-4 focus:ring-blue-400 font-medium py-1.5 px-3 rounded-full shadow-md transition flex items-center border border-blue-600 text-sm" title="Resume Semua">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>
            </div>
        </div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Rank</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progres</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Ujian</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-blue-600 uppercase tracking-wider">Live Score</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" x-data x-init="
                Livewire.hook('message.processed', () => {
                    const rows = document.querySelectorAll('.leaderboard-row');
                    rows.forEach(row => {
                        row.classList.add('bg-blue-50/30');
                        setTimeout(() => row.classList.remove('bg-blue-50/30'), 1000);
                    });
                });
            ">
                @forelse ($sessions as $index => $session)
                    <tr class="leaderboard-row transition-colors duration-500 {{ $index < 3 ? 'bg-gradient-to-r from-yellow-50/30 to-white' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($index === 0)
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 font-bold text-lg shadow-sm border border-yellow-200">🥇</span>
                            @elseif($index === 1)
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-500 font-bold text-lg shadow-sm border border-gray-200">🥈</span>
                            @elseif($index === 2)
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-600 font-bold text-lg shadow-sm border border-orange-200">🥉</span>
                            @else
                                <span class="text-gray-500 font-bold">{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $session->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $session->user->nik ?? $session->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($session->status === 'completed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                            @elseif($session->is_paused)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 shadow-sm border border-red-200">Dijeda (Paused)</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 animate-pulse">In Progress</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @php
                                $totalQuestions = $exam->total_questions;
                                $answered = $session->stat_answered;
                            @endphp
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-200 rounded-full h-1.5 mb-1 mt-1">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $totalQuestions > 0 ? ($answered / $totalQuestions) * 100 : 0 }}%"></div>
                                </div>
                                @if($session->violation_count > 0)
                                    <button wire:click="viewViolations({{ $session->id }})" class="ml-3 px-2 py-0.5 bg-red-100 border border-red-200 text-red-700 text-xs font-bold rounded-full flex items-center shadow-sm cursor-pointer hover:bg-red-200 hover:text-red-900 transition-colors" title="Lihat detail pelanggaran">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        {{ $session->violation_count }}x
                                    </button>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2 text-xs mt-1.5">
                                <span class="text-gray-700 font-medium">{{ $answered }} / {{ $totalQuestions }} Terisi</span>
                                @if($session->status === 'completed')
                                    <span class="text-gray-300">|</span>
                                    <span class="text-green-600 font-medium" title="Benar">{{ $session->stat_correct }} B</span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-red-500 font-medium" title="Salah">{{ $session->stat_wrong }} S</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex flex-col space-y-1">
                                <div class="flex items-center text-xs">
                                    <span class="w-12 text-gray-400">Mulai:</span>
                                    <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($session->started_at)->format('H:i:s') }}</span>
                                </div>
                                @if($session->status === 'completed')
                                <div class="flex items-center text-xs">
                                    <span class="w-12 text-gray-400">Selesai:</span>
                                    <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($session->completed_at)->format('H:i:s') }}</span>
                                </div>
                                <div class="flex items-center text-xs text-gray-400 mt-0.5">
                                    Durasi: {{ (int) \Carbon\Carbon::parse($session->started_at)->diffInMinutes($session->completed_at) }} menit
                                </div>
                                @else
                                <div class="flex items-center text-xs">
                                    <span class="w-12 text-gray-400">Sisa:</span>
                                    <span class="font-medium font-mono {{ $session->remaining_seconds < 60 ? 'text-red-600' : 'text-blue-600' }}"
                                          x-data="{ 
                                              remaining: {{ $session->remaining_seconds }},
                                              isPaused: {{ $session->is_paused ? 'true' : 'false' }},
                                              interval: null,
                                              init() {
                                                  if (!this.isPaused) {
                                                      this.interval = setInterval(() => {
                                                          if(this.remaining > 0) this.remaining--;
                                                      }, 1000);
                                                  }
                                              }
                                          }"
                                          x-text="String(Math.floor(remaining / 3600)).padStart(2, '0') + ':' + String(Math.floor((remaining % 3600) / 60)).padStart(2, '0') + ':' + String(Math.floor(remaining % 60)).padStart(2, '0')">
                                        {{ str_pad(floor($session->remaining_seconds / 3600), 2, '0', STR_PAD_LEFT) }}:{{ str_pad(floor(($session->remaining_seconds % 3600) / 60), 2, '0', STR_PAD_LEFT) }}:{{ str_pad(floor($session->remaining_seconds % 60), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                            @if($session->status === 'completed')
                                @if($session->score >= $exam->passing_grade)
                                    <span class="text-green-600 text-lg">{{ round($session->score) }}</span>
                                @else
                                    <span class="text-red-600 text-lg">{{ round($session->score) }}</span>
                                @endif
                            @elseif($session->is_paused)
                                <span class="text-gray-500 text-lg line-through" title="Ujian sedang dijeda">{{ round($session->live_score) }}</span>
                            @else
                                <span class="text-blue-600 text-lg animate-pulse">{{ round($session->live_score) }} <span class="text-xs font-normal text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full ml-1">Berjalan</span></span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                @if($session->status !== 'completed')
                                    @if($session->is_paused)
                                        <button wire:click="resumeSession({{ $session->id }})" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition inline-flex items-center font-bold">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Resume
                                        </button>
                                    @else
                                        <button wire:click="pauseSession({{ $session->id }})" class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 px-3 py-1.5 rounded-lg transition inline-flex items-center font-bold">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Pause
                                        </button>
                                    @endif
                                @endif
                                @if($session->status === 'completed')
                                <button wire:click="viewResult({{ $session->id }})" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition inline-flex items-center font-bold">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat Hasil
                                </button>
                            @endif
                            <button wire:click="resetDevice({{ $session->id }})" wire:confirm="Anda yakin ingin melepaskan kunci perangkat untuk sesi ujian ini? Jawaban peserta akan tetap aman dan peserta bisa melanjutkan dari perangkat lain." class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg transition inline-flex items-center font-bold" title="Lepaskan Perangkat (Bisa Lanjut di Komputer Lain)">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
                                Buka Kunci
                            </button>
                            <button wire:click="resetSession({{ $session->id }})" wire:confirm="Anda yakin ingin mereset/menghapus ujian peserta ini? Seluruh jawaban yang sudah diisi akan hilang dan peserta harus mengulang dari awal." class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition inline-flex items-center font-bold">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Reset
                            </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Belum ada peserta yang mengikuti ujian ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Result Modal -->
    @if($showResultModal && $selectedSession)
        <div class="fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeResultModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100 flex justify-between items-center sticky top-0 z-10">
                        <div>
                            <h3 class="text-xl leading-6 font-extrabold text-gray-900" id="modal-title">
                                Detail Hasil Ujian: {{ $selectedSession->user->name }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Skor Akhir: <span class="font-bold {{ $selectedSession->score >= $exam->passing_grade ? 'text-green-600' : 'text-red-600' }}">{{ round($selectedSession->score) }}</span></p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.exams.session.print', $selectedSession->id) }}" target="_blank" class="text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-full px-4 py-2 font-bold flex items-center transition text-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak Hasil
                            </a>
                            <button wire:click="closeResultModal" class="text-gray-400 hover:text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 max-h-[70vh] overflow-y-auto bg-gray-50 space-y-6">
                        @forelse($selectedSession->answers as $index => $answer)
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-bold text-gray-800">Soal No. {{ $index + 1 }}</h4>
                                    @if($answer->is_correct)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Benar
                                        </span>
                                    @elseif($answer->is_correct === 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Salah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            Kosong / Esai
                                        </span>
                                    @endif
                                </div>
                                <div class="prose max-w-none text-gray-700 text-sm mb-4">
                                    {!! nl2br(e($answer->question->text ?? 'Soal tidak ditemukan')) !!}
                                </div>
                                
                                @if($answer->question && $answer->question->type === 'multiple_choice')
                                    <div class="mt-3 space-y-2">
                                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jawaban Peserta:</div>
                                        @if($answer->option)
                                            <div class="p-3 rounded-lg border flex items-center {{ $answer->is_correct ? 'bg-green-50 border-green-200 text-green-900' : 'bg-red-50 border-red-200 text-red-900' }}">
                                                <div class="flex-1 text-sm font-medium">{{ $answer->option->text }}</div>
                                            </div>
                                        @else
                                            <div class="p-3 rounded-lg border bg-gray-50 border-gray-200 text-gray-500 italic text-sm">
                                                Tidak dijawab
                                            </div>
                                        @endif

                                        @if(!$answer->is_correct)
                                            @php
                                                // Find the correct option for this question
                                                $correctOption = \App\Models\Option::where('question_id', $answer->question_id)->where('is_correct', true)->first();
                                            @endphp
                                            @if($correctOption)
                                                <div class="mt-4">
                                                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kunci Jawaban yang Benar:</div>
                                                    <div class="p-3 rounded-lg border bg-blue-50 border-blue-200 text-blue-900 flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        <div class="flex-1 text-sm font-medium">{{ $correctOption->text }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-3">
                                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jawaban Esai:</div>
                                        <div class="p-3 rounded-lg border bg-gray-50 border-gray-200 text-gray-900 text-sm whitespace-pre-wrap">
                                            {{ $answer->answer_text ?? 'Tidak dijawab' }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-10">
                                Tidak ada data jawaban untuk sesi ujian ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            </div>
        </div>
    @endif

    <!-- Violation Modal -->
    @if($showViolationModal && $violationSession)
        <div class="fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeViolationModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    <div class="bg-red-50 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-red-100 flex justify-between items-center sticky top-0 z-10">
                        <div>
                            <h3 class="text-xl leading-6 font-extrabold text-red-800" id="modal-title">
                                Detail Pelanggaran: {{ $violationSession->user->name }}
                            </h3>
                            <p class="text-sm text-red-600 mt-1">Total Pelanggaran: <span class="font-bold">{{ count($violationLogs) }}x</span></p>
                        </div>
                        <button wire:click="closeViolationModal" class="text-red-400 hover:text-red-600 bg-red-100 hover:bg-red-200 rounded-full p-2 transition">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="px-6 py-4 max-h-[60vh] overflow-y-auto bg-white space-y-4">
                        @forelse($violationLogs as $log)
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex gap-4 items-start">
                                <div class="bg-red-100 text-red-600 p-2 rounded-lg shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-gray-800 text-sm">Aktivitas Mencurigakan</h4>
                                        <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-md border shadow-sm">
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        {{ str_replace('Peserta ' . $violationSession->user->name . ' (NIK: ' . $violationSession->user->nik . ') melakukan pelanggaran: ', '', $log->description) }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
                                <p>Tidak ada catatan pelanggaran.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
