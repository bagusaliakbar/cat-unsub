<div>
    @if(!$isValidated)
        <!-- Form Login (ditampilkan di split-screen layout) -->
        <div class="w-full max-w-md mx-auto">
            <div class="bg-white py-8 px-6 sm:px-10 shadow-xl sm:rounded-2xl border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold text-gray-900">Masuk Ujian</h2>
                    <p class="text-sm text-gray-500 mt-2">Silakan masukkan Token Ujian Anda untuk melanjutkan.</p>
                </div>

                <form wire:submit.prevent="validateParticipant" class="space-y-6">
                    <div>
                        <label for="participant_number" class="block text-sm font-medium text-gray-700">
                           Token Ujian
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            </div>
                            <input wire:model="participant_number" id="participant_number" type="text" required class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-xl py-3 bg-gray-50" placeholder="Masukkan Token Ujian">
                        </div>
                        @error('participant_number') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
                            Masuk
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    @else
        <!-- Dashboard Peserta (ditampilkan di full-screen layout dengan overlay) -->
        <div class="fixed inset-0 z-50 bg-blue-50 overflow-y-auto w-full h-full flex flex-col">
            
            <!-- Full Width Header -->
            <header class="bg-white border-b border-gray-100 shadow-sm w-full shrink-0">
                <div class="w-full px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-50 p-1.5 rounded-lg border border-blue-100">
                                <img src="{{ asset('images/logo.png') }}" class="w-7 h-7 object-contain" alt="Logo UNSUB">
                            </div>
                            <span class="text-xl font-bold tracking-wider text-blue-900">Computer Assisted Test Universitas Subang</span>
                        </div>
                        
                        <!-- Logout Button in Header -->
                        <button wire:click="logout" type="button" class="flex items-center text-sm text-gray-500 hover:text-red-600 font-medium transition-colors bg-gray-50 hover:bg-red-50 px-4 py-2 rounded-lg border border-gray-100 hover:border-red-100">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <div class="flex-grow py-8 px-4 sm:px-6 lg:px-8 flex flex-col items-center w-full">
                <div class="w-full max-w-full mx-auto">
                    <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
                        <!-- Header Card -->
                        <div class="bg-gradient-to-r from-blue-800 via-blue-700 to-blue-600 px-8 py-8 text-white relative">
                            <div class="absolute top-0 right-0 p-6 opacity-10 pointer-events-none transform translate-x-4 -translate-y-4">
                                <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                            </div>
                            
                            <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6 text-center md:text-left">
                                @if($participant->profile_photo_path)
                                    <img src="{{ $participant->profile_photo_url }}" alt="Profile" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover bg-white shrink-0">
                                @else
                                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-blue-100 flex items-center justify-center text-blue-500 shrink-0">
                                        <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                @endif
                                <div class="pt-2">
                                    <span class="bg-blue-900/50 text-blue-100 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest border border-blue-400/30">KARTU PESERTA</span>
                                    <h2 class="text-3xl font-extrabold mt-3 mb-1">{{ $participant->name }}</h2>
                                    <p class="text-blue-200 font-mono tracking-wider text-lg">{{ $participant->participant_number ?? $participant->nik }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Info Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 border-b border-gray-100">
                            <div class="p-6 md:px-8 md:py-6 border-b md:border-r border-gray-100 bg-gray-50/50">
                                <span class="block text-gray-500 text-xs uppercase tracking-wider font-bold mb-1">Nomor Induk Kependudukan / NIK</span>
                                <span class="font-medium text-gray-900 text-lg">{{ $participant->nik ?? '-' }}</span>
                            </div>
                            <div class="p-6 md:px-8 md:py-6 border-b border-gray-100 bg-gray-50/50">
                                <span class="block text-gray-500 text-xs uppercase tracking-wider font-bold mb-1">Desa & Kecamatan</span>
                                <span class="font-medium text-gray-900 text-lg">
                                    {{ $participant->desa ?: ($participant->institution ?: '-') }}
                                    @if($participant->kecamatan)
                                        <span class="text-sm text-gray-600 font-normal block mt-0.5">Kec. {{ $participant->kecamatan }}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="p-6 md:px-8 md:py-6 border-b md:border-b-0 md:border-r border-gray-100 bg-gray-50/50">
                                <span class="block text-gray-500 text-xs uppercase tracking-wider font-bold mb-1">Tempat, Tanggal Lahir</span>
                                <span class="font-medium text-gray-900 text-lg">
                                    {{ $participant->birth_place ?? '-' }}, 
                                    {{ $participant->birth_date ? \Carbon\Carbon::parse($participant->birth_date)->format('d F Y') : '-' }}
                                </span>
                            </div>
                            <div class="p-6 md:px-8 md:py-6 bg-gray-50/50">
                                <span class="block text-gray-500 text-xs uppercase tracking-wider font-bold mb-1">Pendidikan Terakhir</span>
                                <span class="font-medium text-gray-900 text-lg">{{ $participant->latest_education ?? '-' }}</span>
                            </div>
                            <div class="p-6 md:px-8 md:py-6 border-t border-gray-100 md:col-span-2 bg-gray-50/50">
                                <span class="block text-gray-500 text-xs uppercase tracking-wider font-bold mb-1">Alamat</span>
                                <span class="font-medium text-gray-900 text-lg">{{ $participant->address ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Exams List -->
                        <div class="p-8" wire:poll.10s>
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    Daftar Ujian Anda
                                </h3>
                                <span class="bg-gray-100 text-gray-700 text-sm font-bold px-3 py-1 rounded-full">{{ count($assignedExams) }} Tersedia</span>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($assignedExams as $exam)
                                    <div class="bg-white border-2 border-gray-100 rounded-2xl p-5 hover:border-blue-400 hover:shadow-lg transition-all duration-300 group flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                                        <div class="w-full sm:w-auto">
                                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-700 transition-colors">{{ $exam->title }}</h4>
                                            <div class="flex flex-wrap items-center mt-2 text-sm text-gray-600 gap-3 font-medium">
                                                <span class="flex items-center bg-gray-100 px-2.5 py-1 rounded-md shrink-0">
                                                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                                    {{ $exam->duration_minutes }} Menit
                                                </span>
                                                <span class="flex items-center bg-gray-100 px-2.5 py-1 rounded-md shrink-0">
                                                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                                    {{ $exam->total_questions }} Soal
                                                </span>
                                                @if($exam->start_time || $exam->end_time)
                                                <span class="flex items-center bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md shrink-0 border border-blue-100">
                                                    <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    @if($exam->start_time && $exam->end_time)
                                                        {{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y, H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                                                    @elseif($exam->start_time)
                                                        Mulai: {{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y, H:i') }}
                                                    @else
                                                        Selesai: {{ \Carbon\Carbon::parse($exam->end_time)->format('d M Y, H:i') }}
                                                    @endif
                                                </span>
                                                @endif
                                                @if($exam->location)
                                                <span class="flex items-center bg-amber-50 text-amber-700 px-2.5 py-1 rounded-md shrink-0 border border-amber-100">
                                                    <svg class="w-4 h-4 mr-1.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    {{ $exam->location }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        @php
                                            $session = \App\Models\ExamSession::where('exam_id', $exam->id)
                                                ->where('user_id', $participant->id)
                                                ->first();
                                            $isCompleted = $session && in_array($session->status, ['completed', 'finished']);
                                            $isStarted = $session && $session->status === 'started';
                                            
                                            $now = now();
                                            $isUpcoming = $exam->start_time && $now->lessThan(\Carbon\Carbon::parse($exam->start_time));
                                            $isExpired = $exam->end_time && $now->greaterThan(\Carbon\Carbon::parse($exam->end_time));
                                        @endphp
                                        
                                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto mt-4 sm:mt-0 justify-end">
                                            <button wire:click="viewRules({{ $exam->id }})" wire:loading.attr="disabled" type="button" class="w-full sm:w-auto bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 px-5 py-3 rounded-xl font-bold shadow-sm transition-all flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                Tata Tertib
                                            </button>

                                            @if($isCompleted)
                                                @if($exam->is_simulation)
                                                    <button wire:loading.attr="disabled" wire:click="retakeSimulation({{ $exam->id }})" wire:confirm="Anda akan mengulang ujian simulasi ini dari awal. Nilai sebelumnya akan dihapus. Lanjutkan?" type="button" class="w-full sm:w-auto bg-purple-500 hover:bg-purple-600 disabled:opacity-50 text-white px-6 py-3 rounded-xl font-bold shadow-md hover:shadow-lg focus:ring-4 focus:ring-purple-300 transition-all transform hover:-translate-y-0.5 flex items-center justify-center shrink-0">
                                                        Kerjakan Ulang
                                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                    </button>
                                                @else
                                                    <button type="button" class="w-full sm:w-auto bg-gray-400 text-white px-6 py-3 rounded-xl font-bold shadow-md cursor-not-allowed flex items-center justify-center shrink-0" disabled>
                                                        Sudah Dikerjakan
                                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                @endif
                                            @elseif($isStarted)
                                                @if($isExpired)
                                                    <button type="button" class="w-full sm:w-auto bg-gray-400 text-white px-6 py-3 rounded-xl font-bold shadow-md cursor-not-allowed flex items-center justify-center shrink-0" disabled>
                                                        Waktu Habis
                                                    </button>
                                                @else
                                                    <button wire:loading.attr="disabled" wire:click="startExam({{ $exam->id }})" type="button" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white px-6 py-3 rounded-xl font-bold shadow-md hover:shadow-lg focus:ring-4 focus:ring-amber-300 transition-all transform hover:-translate-y-0.5 flex items-center justify-center shrink-0">
                                                        Lanjutkan
                                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                    </button>
                                                @endif
                                            @else
                                                @if($isUpcoming)
                                                    <button type="button" class="w-full sm:w-auto bg-gray-400 text-white px-6 py-3 rounded-xl font-bold shadow-md cursor-not-allowed flex items-center justify-center shrink-0" disabled>
                                                        Belum Dimulai
                                                    </button>
                                                @elseif($isExpired)
                                                    <button type="button" class="w-full sm:w-auto bg-gray-400 text-white px-6 py-3 rounded-xl font-bold shadow-md cursor-not-allowed flex items-center justify-center shrink-0" disabled>
                                                        Waktu Habis
                                                    </button>
                                                @else
                                                    <button wire:loading.attr="disabled" wire:click="showRules({{ $exam->id }})" type="button" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white px-6 py-3 rounded-xl font-bold shadow-md hover:shadow-lg focus:ring-4 focus:ring-green-300 transition-all transform hover:-translate-y-0.5 flex items-center justify-center shrink-0">
                                                        Mulai Kerjakan
                                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Konfirmasi Ujian -->
        @if($confirmingExam)
        <div class="fixed inset-0 overflow-y-auto" style="z-index: 9990;" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" wire:click="cancelStart"></div>
            <div class="min-h-full flex items-center justify-center p-4 text-center">
                <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden text-left my-8 border border-gray-100">
                    <div class="bg-blue-600 px-6 py-5 flex justify-between items-center shrink-0">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Konfirmasi Ujian
                        </h3>
                        <button wire:click="cancelStart" class="text-blue-100 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="p-6 md:p-8 overflow-y-auto">
                        <div class="mb-5">
                            <h4 class="text-xl font-bold text-gray-900">{{ $confirmingExam->title }}</h4>
                            <p class="text-sm text-gray-500 mt-1">Silakan konfirmasi kesiapan Anda sebelum memulai ujian.</p>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 text-blue-800 text-sm flex items-start">
                            <svg class="w-6 h-6 text-blue-600 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <strong>Penting:</strong> Apakah Anda sudah membaca dan memahami seluruh Tata Tertib Ujian?
                            </div>
                        </div>
                        
                        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start">
                            <svg class="w-6 h-6 text-amber-600 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <div class="text-sm text-amber-800">
                                <strong>Perhatian:</strong> Waktu ujian akan mulai berjalan (<strong>{{ $confirmingExam->duration_minutes }} menit</strong>) segera setelah Anda menekan tombol di bawah. Pastikan koneksi internet Anda stabil.
                            </div>
                        </div>
                        @if($confirmingExam->token)
                        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-5">
                            <label for="input_token" class="block text-sm font-bold text-blue-900 mb-2">Token Ujian</label>
                            <p class="text-xs text-blue-700 mb-3">Ujian ini memerlukan token. Silakan masukkan token yang diberikan oleh pengawas.</p>
                            <input type="text" id="input_token" wire:model="input_token" class="bg-white border border-blue-300 text-gray-900 text-lg font-mono tracking-widest uppercase rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm text-center" placeholder="MASUKKAN TOKEN">
                            @error('input_token') <span class="text-red-500 text-xs mt-2 block font-medium">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        @error('general_error')
                        <div class="mt-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-xl text-center font-bold flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <div class="p-6 border-t border-gray-100 bg-gray-50 flex flex-col-reverse sm:flex-row justify-end sm:space-x-3 shrink-0 gap-3 sm:gap-0">
                        <button wire:click="cancelStart" type="button" class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-bold transition-colors">
                            Batal
                        </button>
                        <button onclick="requestFullScreen()" wire:loading.attr="disabled" wire:click="startExam({{ $confirmingExam->id }})" type="button" class="w-full sm:w-auto px-6 py-3 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white rounded-xl font-bold shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                            Saya Mengerti & Mulai
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Modal Tata Tertib Peserta (Infografis Resmi) -->
        @if($viewingRulesExam)
        <div class="fixed inset-0 overflow-y-auto" style="z-index: 9999;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop Gelap Pekat -->
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" wire:click="closeRules"></div>
            
            <!-- Modal Container: Top-aligned agar header selalu terlihat rapi saat dibuka -->
            <div class="min-h-full flex items-start justify-center p-3 sm:p-5 md:p-8 text-center">
                <div class="relative w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden text-left my-6 sm:my-8 border border-slate-200">
                    
                    <!-- Header Infografis Modern (Deep Navy + Amber Badge) -->
                    <div class="px-6 py-6 sm:px-8 relative shrink-0 border-b border-blue-900" style="background: linear-gradient(135deg, #091a32 0%, #0f2b52 50%, #16244f 100%) !important; color: #ffffff !important;">
                        <div class="flex justify-between items-center gap-4">
                            <div class="flex-1">
                                <div class="inline-block px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider mb-2 shadow-xs" style="background-color: #f59e0b !important; color: #0f172a !important;">
                                    CAT SELEKSI BAKAL CALON KEPALA DESA 2026
                                </div>
                                <h3 class="text-2xl sm:text-3xl font-black tracking-tight uppercase" style="color: #ffffff !important;">
                                    ATURAN PENTING BAGI PESERTA CAT
                                </h3>
                                <p class="text-xs sm:text-sm mt-1 font-semibold" style="color: #bfdbfe !important;">
                                    Wajib dibaca sebelum memasuki Lab CAT &bull; {{ $viewingRulesExam->title }}
                                </p>
                            </div>
                            
                            <div class="flex items-center gap-3 shrink-0">
                                <button wire:click="closeRules" type="button" class="p-2.5 rounded-xl transition-all hover:bg-white/20" style="color: #ffffff !important; background-color: rgba(255, 255, 255, 0.15) !important;" title="Tutup">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Body: Grid Kartu Infografis -->
                    <div class="p-4 sm:p-6 md:p-7 space-y-4 sm:space-y-5" style="background-color: #f1f5f9 !important;">

                        @if($viewingRulesExam->rules)
                        <!-- Catatan Khusus Ujian -->
                        <div class="bg-amber-50 border border-amber-300 rounded-2xl p-4 sm:p-5 text-amber-900 shadow-sm">
                            <div class="flex items-center text-sm font-bold text-amber-800 mb-2">
                                <svg class="w-5 h-5 mr-2 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Catatan Khusus Ujian:
                            </div>
                            <div class="text-xs sm:text-sm leading-relaxed prose prose-sm max-w-none text-amber-900">
                                {!! nl2br(e($viewingRulesExam->rules)) !!}
                            </div>
                        </div>
                        @endif

                        <!-- Grid 10 Kartu Infografis -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
                            
                            <!-- 1. KEHADIRAN & KETERLAMBATAN (Biru) -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                                <div class="px-4 py-2.5 border-b flex items-center justify-between" style="background-color: #dbeafe !important; border-color: #bfdbfe !important;">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-6 h-6 rounded-full font-black text-xs flex items-center justify-center shrink-0 shadow-sm" style="background-color: #1d4ed8 !important; color: #ffffff !important;">1</span>
                                        <h4 class="font-black text-sm sm:text-[15px] uppercase tracking-wide" style="color: #1e3a8a !important;">Kehadiran & Keterlambatan</h4>
                                    </div>
                                    <svg class="w-4 h-4" style="color: #2563eb !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                    <div class="hidden sm:flex flex-col items-center justify-center w-20 shrink-0 select-none pt-1">
                                        <svg class="w-16 h-16 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="32" cy="34" r="22" fill="#eff6ff" stroke="#2563eb" stroke-width="3"/>
                                            <path d="M32 20V34L40 38" stroke="#1e3a8a" stroke-width="3" stroke-linecap="round"/>
                                            <circle cx="32" cy="34" r="3" fill="#1e3a8a"/>
                                            <path d="M16 14C13 17 11 20 11 20" stroke="#ef4444" stroke-width="3" stroke-linecap="round"/>
                                            <path d="M48 14C51 17 53 20 53 20" stroke="#ef4444" stroke-width="3" stroke-linecap="round"/>
                                            <path d="M19 12L25 18" stroke="#2563eb" stroke-width="3.5" stroke-linecap="round"/>
                                            <path d="M45 12L39 18" stroke="#2563eb" stroke-width="3.5" stroke-linecap="round"/>
                                            <path d="M8 28C6 31 6 37 8 40" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M56 28C58 31 58 37 56 40" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <ul class="text-xs sm:text-[13px] text-slate-700 space-y-2 leading-relaxed list-disc list-outside pl-4 marker:text-blue-600">
                                            <li>Peserta wajib hadir di Auditorium <strong class="text-rose-600 font-bold">sebelum pukul 08.00</strong> untuk mengikuti pembukaan.</li>
                                            <li>Peserta wajib mengikuti sesi sesuai pembagian yang telah ditetapkan.</li>
                                            <li>Peserta yang terlambat tetap mengikuti sesi yang telah ditentukan.</li>
                                            <li><strong class="text-rose-600 font-bold">Waktu ujian tidak diperpanjang</strong> karena keterlambatan.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. IDENTITAS PESERTA (Oranye) -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                                <div class="px-4 py-2.5 border-b flex items-center justify-between" style="background-color: #ffedd5 !important; border-color: #fed7aa !important;">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-6 h-6 rounded-full font-black text-xs flex items-center justify-center shrink-0 shadow-sm" style="background-color: #ea580c !important; color: #ffffff !important;">2</span>
                                        <h4 class="font-black text-sm sm:text-[15px] uppercase tracking-wide" style="color: #9a3412 !important;">Identitas Peserta</h4>
                                    </div>
                                    <svg class="w-4 h-4" style="color: #ea580c !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                    <div class="hidden sm:flex flex-col items-center justify-center w-20 shrink-0 select-none pt-1">
                                        <svg class="w-16 h-16 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="6" y="12" width="52" height="40" rx="5" fill="#f8fafc" stroke="#ea580c" stroke-width="2.5"/>
                                            <rect x="6" y="12" width="52" height="10" rx="4" fill="#ea580c"/>
                                            <circle cx="20" cy="34" r="7" fill="#fed7aa"/>
                                            <circle cx="20" cy="32" r="3.5" fill="#9a3412"/>
                                            <path d="M14 40C14 37 17 37 20 37C23 37 26 37 26 40" fill="#9a3412"/>
                                            <line x1="32" y1="29" x2="50" y2="29" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                                            <line x1="32" y1="35" x2="48" y2="35" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                                            <line x1="32" y1="41" x2="44" y2="41" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <ul class="text-xs sm:text-[13px] text-slate-700 space-y-2 leading-relaxed list-disc list-outside pl-4 marker:text-orange-500">
                                            <li>Peserta wajib membawa <strong class="text-slate-900 font-bold">identitas/dokumen yang dipersyaratkan panitia (asli)</strong>.</li>
                                            <li>Peserta wajib mengikuti pemeriksaan sebelum masuk Lab CAT.</li>
                                            <li>Peserta hanya boleh menempati komputer yang telah ditentukan.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. PEMBAGIAN SESI & RUANGAN (Hijau/Emerald) -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                                <div class="px-4 py-2.5 border-b flex items-center justify-between" style="background-color: #d1fae5 !important; border-color: #a7f3d0 !important;">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-6 h-6 rounded-full font-black text-xs flex items-center justify-center shrink-0 shadow-sm" style="background-color: #059669 !important; color: #ffffff !important;">3</span>
                                        <h4 class="font-black text-sm sm:text-[15px] uppercase tracking-wide" style="color: #065f46 !important;">Pembagian Sesi & Ruangan</h4>
                                    </div>
                                    <svg class="w-4 h-4" style="color: #059669 !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                    <div class="hidden sm:flex flex-col items-center justify-center w-20 shrink-0 select-none pt-1">
                                        <svg class="w-16 h-16 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="10" y="16" width="44" height="40" rx="3" fill="#ecfdf5" stroke="#059669" stroke-width="2.5"/>
                                            <rect x="14" y="20" width="36" height="10" rx="2" fill="#059669"/>
                                            <text x="32" y="27" fill="#ffffff" font-size="7" font-weight="900" text-anchor="middle" font-family="sans-serif">LAB CAT</text>
                                            <rect x="16" y="36" width="8" height="8" rx="1" fill="#a7f3d0" stroke="#059669" stroke-width="1.5"/>
                                            <rect x="40" y="36" width="8" height="8" rx="1" fill="#a7f3d0" stroke="#059669" stroke-width="1.5"/>
                                            <rect x="27" y="36" width="10" height="20" rx="1" fill="#34d399"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <ul class="text-xs sm:text-[13px] text-slate-700 space-y-2 leading-relaxed list-disc list-outside pl-4 marker:text-emerald-600">
                                            <li>Terdapat <strong class="text-slate-900 font-bold">2 sesi CAT</strong>.</li>
                                            <li>Peserta wajib mengikuti sesi dan ruangan sesuai daftar peserta.</li>
                                            <li><strong class="text-slate-900 font-bold">Lab CAT 1</strong> dan <strong class="text-slate-900 font-bold">Lab CAT 2</strong> digunakan sesuai pembagian peserta.</li>
                                            <li>Peserta <strong class="text-rose-600 font-bold">tidak diperkenankan berpindah sesi atau ruangan</strong> tanpa persetujuan panitia.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. SIMULASI CAT (Ungu/Indigo) -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                                <div class="px-4 py-2.5 border-b flex items-center justify-between" style="background-color: #ede9fe !important; border-color: #ddd6fe !important;">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-6 h-6 rounded-full font-black text-xs flex items-center justify-center shrink-0 shadow-sm" style="background-color: #7c3aed !important; color: #ffffff !important;">4</span>
                                        <h4 class="font-black text-sm sm:text-[15px] uppercase tracking-wide" style="color: #5b21b6 !important;">Simulasi CAT</h4>
                                    </div>
                                    <svg class="w-4 h-4" style="color: #7c3aed !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                    <div class="hidden sm:flex flex-col items-center justify-center w-20 shrink-0 select-none pt-1">
                                        <svg class="w-16 h-16 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="8" y="10" width="48" height="34" rx="3" fill="#312e81"/>
                                            <rect x="11" y="13" width="42" height="28" rx="2" fill="#e0e7ff"/>
                                            <rect x="15" y="17" width="5" height="5" rx="1" fill="#4f46e5"/>
                                            <line x1="23" y1="20" x2="38" y2="20" stroke="#4f46e5" stroke-width="2" stroke-linecap="round"/>
                                            <rect x="15" y="25" width="5" height="5" rx="1" fill="#4f46e5"/>
                                            <line x1="23" y1="28" x2="35" y2="28" stroke="#4f46e5" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M36 28L44 36L40 37L43 43L40 44L37 38L33 41Z" fill="#1e1b4b"/>
                                            <rect x="28" y="44" width="8" height="8" fill="#64748b"/>
                                            <ellipse cx="32" cy="52" rx="14" ry="3" fill="#475569"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <ul class="text-xs sm:text-[13px] text-slate-700 space-y-2 leading-relaxed list-disc list-outside pl-4 marker:text-purple-600">
                                            <li>Sebelum CAT dimulai, peserta mengikuti simulasi selama <strong class="text-rose-600 font-bold">10 menit</strong>.</li>
                                            <li>Simulasi digunakan untuk memahami cara login, membuka soal, memilih jawaban, berpindah soal, dan menggunakan sistem CAT.</li>
                                            <li>Peserta wajib memperhatikan instruksi pengawas.</li>
                                            <li><strong class="text-slate-900 font-bold">Simulasi bukan bagian dari penilaian CAT.</strong></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. PELAKSANAAN UJIAN (Biru) -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                                <div class="px-4 py-2.5 border-b flex items-center justify-between" style="background-color: #dbeafe !important; border-color: #bfdbfe !important;">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-6 h-6 rounded-full font-black text-xs flex items-center justify-center shrink-0 shadow-sm" style="background-color: #1d4ed8 !important; color: #ffffff !important;">5</span>
                                        <h4 class="font-black text-sm sm:text-[15px] uppercase tracking-wide" style="color: #1e3a8a !important;">Pelaksanaan Ujian</h4>
                                    </div>
                                    <svg class="w-4 h-4" style="color: #2563eb !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                    <div class="hidden sm:flex flex-col items-center justify-center w-20 shrink-0 select-none pt-1">
                                        <svg class="w-16 h-18 drop-shadow-sm" viewBox="0 0 64 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="8" y="8" width="48" height="58" rx="4" fill="#ffffff" stroke="#2563eb" stroke-width="2.5"/>
                                            <rect x="8" y="8" width="48" height="14" rx="3" fill="#2563eb"/>
                                            <text x="32" y="18" fill="#ffffff" font-size="8" font-weight="900" text-anchor="middle" font-family="sans-serif">100 SOAL</text>
                                            <path d="M14 30L17 33L23 27" stroke="#16a34a" stroke-width="2" stroke-linecap="round"/>
                                            <line x1="27" y1="30" x2="46" y2="30" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M14 40L17 43L23 37" stroke="#16a34a" stroke-width="2" stroke-linecap="round"/>
                                            <line x1="27" y1="40" x2="42" y2="40" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                                            <circle cx="44" cy="54" r="11" fill="#eff6ff" stroke="#2563eb" stroke-width="2"/>
                                            <path d="M44 48V54L48 56" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <ul class="text-xs sm:text-[13px] text-slate-700 space-y-2 leading-relaxed list-disc list-outside pl-4 marker:text-blue-600">
                                            <li>Jumlah soal CAT sebanyak <strong class="text-rose-600 font-bold">100 soal</strong>.</li>
                                            <li>Durasi CAT adalah <strong class="text-rose-600 font-bold">90 menit</strong>.</li>
                                            <li>Peserta mengerjakan soal secara mandiri.</li>
                                            <li>Peserta wajib membaca soal dengan teliti.</li>
                                            <li>Jawaban harus dipilih melalui sistem CAT.</li>
                                            <li>Pastikan jawaban telah tersimpan sesuai mekanisme aplikasi.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 6. SELAMA UJIAN DILARANG (Merah) -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                                <div class="px-4 py-2.5 border-b flex items-center justify-between" style="background-color: #ffe4e6 !important; border-color: #fecdd3 !important;">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-6 h-6 rounded-full font-black text-xs flex items-center justify-center shrink-0 shadow-sm" style="background-color: #e11d48 !important; color: #ffffff !important;">6</span>
                                        <h4 class="font-black text-sm sm:text-[15px] uppercase tracking-wide" style="color: #9f1239 !important;">Selama Ujian Dilarang</h4>
                                    </div>
                                    <svg class="w-4 h-4" style="color: #e11d48 !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                    <div class="hidden sm:flex flex-col items-center justify-center w-20 shrink-0 select-none pt-1">
                                        <svg class="w-16 h-16 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="32" cy="32" r="26" stroke="#dc2626" stroke-width="5" fill="#fef2f2"/>
                                            <line x1="14" y1="14" x2="50" y2="50" stroke="#dc2626" stroke-width="5" stroke-linecap="round"/>
                                            <rect x="22" y="20" width="10" height="18" rx="2" stroke="#475569" stroke-width="2" fill="#ffffff"/>
                                            <circle cx="40" cy="36" r="6" stroke="#475569" stroke-width="2" fill="#ffffff"/>
                                            <circle cx="40" cy="36" r="2.5" fill="#475569"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <ul class="text-xs sm:text-[13px] text-slate-700 space-y-1.5 leading-relaxed list-none pl-0">
                                            <li class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                <span>Bekerja sama atau berkomunikasi dengan peserta lain.</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                <span>Menggunakan HP atau perangkat elektronik yang tidak diperbolehkan.</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                <span>Membuka aplikasi atau website lain.</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                <span>Memotret atau merekam soal / layar CAT.</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                <span>Menyalin atau menyebarkan soal CAT.</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                <span>Mengubah pengaturan komputer.</span>
                                            </li>
                                            <li class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                <span>Mengganggu peserta lain.</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 7. GANGGUAN TEKNIS (Kuning/Amber) -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                                <div class="px-4 py-2.5 border-b flex items-center justify-between" style="background-color: #fef3c7 !important; border-color: #fde68a !important;">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-6 h-6 rounded-full font-black text-xs flex items-center justify-center shrink-0 shadow-sm" style="background-color: #d97706 !important; color: #ffffff !important;">7</span>
                                        <h4 class="font-black text-sm sm:text-[15px] uppercase tracking-wide" style="color: #92400e !important;">Gangguan Teknis</h4>
                                    </div>
                                    <svg class="w-4 h-4" style="color: #d97706 !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                    <div class="hidden sm:flex flex-col items-center justify-center w-20 shrink-0 select-none pt-1">
                                        <svg class="w-16 h-18 drop-shadow-sm" viewBox="0 0 64 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="28" cy="20" r="10" fill="#3b82f6"/>
                                            <path d="M14 46C14 36 20 34 28 34C36 34 42 36 42 46" fill="#1e40af"/>
                                            <path d="M42 38L52 22L56 24L48 42" stroke="#ea580c" stroke-width="3.5" stroke-linecap="round"/>
                                            <circle cx="48" cy="14" r="9" fill="#f59e0b" stroke="#ffffff" stroke-width="2"/>
                                            <text x="48" y="19" fill="#ffffff" font-size="13" font-weight="900" text-anchor="middle" font-family="sans-serif">!</text>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs sm:text-[13px] text-slate-700 leading-relaxed">
                                            Jika terjadi masalah komputer, jaringan, aplikasi, keyboard, mouse, atau perangkat lainnya:
                                        </p>
                                        <div class="bg-amber-100/90 border border-amber-300 rounded-xl p-3 my-2 text-center shadow-xs">
                                            <p class="font-extrabold text-amber-950 text-xs sm:text-[13px] tracking-wide">JANGAN PANIK</p>
                                            <p class="font-extrabold text-amber-950 text-xs sm:text-[13px] tracking-wide">JANGAN MENGUBAH KOMPUTER</p>
                                            <p class="font-black text-rose-700 text-xs sm:text-[13px] tracking-wide mt-0.5">SEGERA ANGKAT TANGAN DAN LAPORKAN KEPADA PENGAWAS.</p>
                                        </div>
                                        <p class="text-xs sm:text-[13px] text-slate-700 leading-relaxed mt-1 list-disc list-outside pl-4">
                                            Peserta tidak diperkenankan memperbaiki atau mengubah konfigurasi komputer sendiri.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- 8. KETIKA WAKTU BERAKHIR / MENYELESAIKAN UJIAN (Teal) -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                                <div class="px-4 py-2.5 border-b flex items-center justify-between" style="background-color: #ccfbf1 !important; border-color: #99f6e4 !important;">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-6 h-6 rounded-full font-black text-xs flex items-center justify-center shrink-0 shadow-sm" style="background-color: #0d9488 !important; color: #ffffff !important;">8</span>
                                        <h4 class="font-black text-sm sm:text-[15px] uppercase tracking-wide" style="color: #115e59 !important;">Ketika Waktu Berakhir / Selesai</h4>
                                    </div>
                                    <svg class="w-4 h-4" style="color: #0d9488 !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                    <div class="hidden sm:flex flex-col items-center justify-center w-20 shrink-0 select-none pt-1">
                                        <svg class="w-16 h-18 drop-shadow-sm" viewBox="0 0 64 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="6" y="8" width="52" height="42" rx="4" fill="#0f172a"/>
                                            <rect x="9" y="11" width="46" height="36" rx="2" fill="#f0fdf4"/>
                                            <circle cx="32" cy="18" r="5" fill="#16a34a"/>
                                            <path d="M29.5 18L31 19.5L34.5 16.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/>
                                            <text x="32" y="28" fill="#15803d" font-size="5" font-weight="900" text-anchor="middle" font-family="sans-serif">Ujian Selesai</text>
                                            <text x="32" y="34" fill="#64748b" font-size="3.5" font-weight="bold" text-anchor="middle" font-family="sans-serif">Skor CAT Anda:</text>
                                            <text x="32" y="43" fill="#1e3a8a" font-size="8.5" font-weight="900" text-anchor="middle" font-family="sans-serif">85</text>
                                            <rect x="28" y="50" width="8" height="8" fill="#64748b"/>
                                            <ellipse cx="32" cy="58" rx="16" ry="3" fill="#475569"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <ul class="text-xs sm:text-[13px] text-slate-700 space-y-2 leading-relaxed list-disc list-outside pl-4 marker:text-teal-600">
                                            <li>Jika peserta sudah selesai mengerjakan soal sebelum waktu habis, pilih menu <strong class="text-rose-600 font-bold">SELESAI</strong>. Skor CAT akan langsung muncul pada layar.</li>
                                            <li>Ketika waktu CAT berakhir, sistem secara otomatis akan menyimpan semua jawaban Anda dan menampilkan skor CAT pada layar.</li>
                                            <li>Peserta tidak perlu melakukan tindakan apa pun, cukup menunggu hingga skor ditampilkan.</li>
                                            <li>Ikuti instruksi pengawas untuk keluar dari sistem dan meninggalkan komputer.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 9. SKOR/HASIL CAT (Ungu) -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                                <div class="px-4 py-2.5 border-b flex items-center justify-between" style="background-color: #ede9fe !important; border-color: #ddd6fe !important;">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-6 h-6 rounded-full font-black text-xs flex items-center justify-center shrink-0 shadow-sm" style="background-color: #7c3aed !important; color: #ffffff !important;">9</span>
                                        <h4 class="font-black text-sm sm:text-[15px] uppercase tracking-wide" style="color: #5b21b6 !important;">Skor / Hasil CAT</h4>
                                    </div>
                                    <svg class="w-4 h-4" style="color: #7c3aed !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                    <div class="hidden sm:flex flex-col items-center justify-center w-20 shrink-0 select-none pt-1">
                                        <svg class="w-16 h-18 drop-shadow-sm" viewBox="0 0 64 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="10" y="8" width="44" height="54" rx="4" fill="#ffffff" stroke="#7c3aed" stroke-width="2.5"/>
                                            <line x1="18" y1="18" x2="36" y2="18" stroke="#7c3aed" stroke-width="2.5" stroke-linecap="round"/>
                                            <line x1="18" y1="24" x2="30" y2="24" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                                            <rect x="18" y="44" width="6" height="10" rx="1" fill="#a78bfa"/>
                                            <rect x="27" y="36" width="6" height="18" rx="1" fill="#7c3aed"/>
                                            <rect x="36" y="30" width="6" height="24" rx="1" fill="#4c1d95"/>
                                            <circle cx="46" cy="46" r="11" fill="#16a34a" stroke="#ffffff" stroke-width="2"/>
                                            <path d="M42 46L45 49L51 43" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <ul class="text-xs sm:text-[13px] text-slate-700 space-y-2 leading-relaxed list-disc list-outside pl-4 marker:text-purple-600">
                                            <li>Hasil yang dihasilkan oleh sistem CAT merupakan <strong class="text-rose-600 font-bold">hasil final</strong> dan <strong class="text-rose-600 font-bold">tidak dapat diganggu gugat</strong>.</li>
                                            <li>Peserta mengikuti prosedur yang ditetapkan panitia terkait pencetakan dan penyampaian hasil.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- 10. SIKAP PESERTA (Biru/Langit) -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                                <div class="px-4 py-2.5 border-b flex items-center justify-between" style="background-color: #e0f2fe !important; border-color: #bae6fd !important;">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-6 h-6 rounded-full font-black text-xs flex items-center justify-center shrink-0 shadow-sm" style="background-color: #0284c7 !important; color: #ffffff !important;">10</span>
                                        <h4 class="font-black text-sm sm:text-[15px] uppercase tracking-wide" style="color: #0369a1 !important;">Sikap Peserta</h4>
                                    </div>
                                    <svg class="w-4 h-4" style="color: #0284c7 !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                    <div class="hidden sm:flex flex-col items-center justify-center w-20 shrink-0 select-none pt-1">
                                        <svg class="w-16 h-16 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="32" cy="22" r="7" fill="#0284c7"/>
                                            <path d="M22 44C22 36 26 34 32 34C38 34 42 36 42 44" fill="#ffffff" stroke="#0284c7" stroke-width="2"/>
                                            <circle cx="16" cy="26" r="5.5" fill="#38bdf8"/>
                                            <path d="M8 46C8 40 12 38 16 38C20 38 24 40 24 46" fill="#e0f2fe" stroke="#38bdf8" stroke-width="2"/>
                                            <circle cx="48" cy="26" r="5.5" fill="#38bdf8"/>
                                            <path d="M40 46C40 40 44 38 48 38C52 38 56 40 56 46" fill="#e0f2fe" stroke="#38bdf8" stroke-width="2"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <ul class="text-xs sm:text-[13px] text-slate-700 space-y-2 leading-relaxed list-disc list-outside pl-4 marker:text-sky-600">
                                            <li>Peserta wajib menjaga ketertiban, kejujuran, kedisiplinan, dan ketenangan dalam seluruh rangkaian kegiatan.</li>
                                            <li>Mengikuti seluruh arahan panitia dan pengawas.</li>
                                            <li>Menjaga nama baik diri sendiri dan kelancaran pelaksanaan CAT.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- JADWAL PELAKSANAAN CAT (Full Width Sesuai Infografis) -->
                            <div class="col-span-1 md:col-span-2 rounded-2xl shadow-sm overflow-hidden border border-blue-900 bg-white">
                                <div class="grid grid-cols-1 md:grid-cols-12 items-stretch">
                                    <!-- Label Kiri: Biru Tua -->
                                    <div class="md:col-span-4 p-4 sm:p-5 flex items-center justify-center md:justify-start gap-3.5 text-white" style="background-color: #0b2545 !important;">
                                        <div class="p-2.5 rounded-xl bg-white/10 shrink-0">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-sm sm:text-base uppercase tracking-wider text-white leading-tight">JADWAL PELAKSANAAN CAT</h4>
                                            <p class="text-[11px] text-blue-200 font-medium mt-0.5">Sesi & Rincian Waktu Ujian</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Sesi 1: Hijau / Teal -->
                                    <div class="md:col-span-4 p-4 border-t md:border-t-0 md:border-r border-slate-200 flex flex-col justify-center bg-emerald-50/40">
                                        <div class="inline-flex items-center justify-center px-3 py-1 rounded-md text-[11px] font-black uppercase tracking-wider mb-2.5 text-white shadow-xs" style="background-color: #059669 !important;">
                                            SESI 1 &ndash; 50 PESERTA
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-center">
                                            <div class="bg-white rounded-lg p-2 border border-emerald-200">
                                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">Simulasi</div>
                                                <div class="text-xs sm:text-[13px] font-extrabold text-emerald-900">09.00 &ndash; 09.10</div>
                                                <div class="text-[10px] font-semibold text-emerald-700">(10 menit)</div>
                                            </div>
                                            <div class="bg-white rounded-lg p-2 border border-emerald-200">
                                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">Pelaksanaan CAT</div>
                                                <div class="text-xs sm:text-[13px] font-extrabold text-emerald-900">09.10 &ndash; 10.40</div>
                                                <div class="text-[10px] font-semibold text-emerald-700">(90 menit)</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sesi 2: Oranye -->
                                    <div class="md:col-span-4 p-4 border-t md:border-t-0 border-slate-200 flex flex-col justify-center bg-orange-50/40">
                                        <div class="inline-flex items-center justify-center px-3 py-1 rounded-md text-[11px] font-black uppercase tracking-wider mb-2.5 text-white shadow-xs" style="background-color: #ea580c !important;">
                                            SESI 2 &ndash; 49 PESERTA
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-center">
                                            <div class="bg-white rounded-lg p-2 border border-orange-200">
                                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">Simulasi</div>
                                                <div class="text-xs sm:text-[13px] font-extrabold text-orange-950">11.00 &ndash; 11.10</div>
                                                <div class="text-[10px] font-semibold text-orange-700">(10 menit)</div>
                                            </div>
                                            <div class="bg-white rounded-lg p-2 border border-orange-200">
                                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">Pelaksanaan CAT</div>
                                                <div class="text-xs sm:text-[13px] font-extrabold text-orange-950">11.10 &ndash; 12.40</div>
                                                <div class="text-[10px] font-semibold text-orange-700">(90 menit)</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PENGUMUMAN AUDITORIUM (Full Width Sesuai Infografis Bawah) -->
                            <div class="col-span-1 md:col-span-2 rounded-2xl shadow-sm overflow-hidden flex flex-col sm:flex-row items-center p-3.5 sm:p-4 gap-3 text-center sm:text-left" style="background-color: #fee2e2 !important; border: 1.5px solid #fca5a5 !important;">
                                <div class="text-2xl shrink-0 select-none">
                                    📢
                                </div>
                                <div class="text-xs sm:text-sm font-medium leading-relaxed" style="color: #7f1d1d !important;">
                                    Seluruh peserta <strong class="font-extrabold text-rose-950">wajib hadir di Auditorium</strong> sebelum pukul <strong class="font-black text-rose-700 underline underline-offset-2">08.00</strong> untuk mengikuti pembukaan.
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <!-- Footer Modal -->
                    <div class="p-4 sm:p-6 border-t border-slate-200 bg-white flex flex-col sm:flex-row justify-between items-center gap-3 shrink-0">
                        <div class="text-xs text-slate-500 text-center sm:text-left flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Harap pastikan seluruh tata tertib telah dipahami dengan baik sebelum memulai tes.
                        </div>
                        <button wire:click="closeRules" type="button" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-md hover:shadow-lg transition-all flex items-center justify-center text-sm">
                            Saya Mengerti & Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>

<script>
    function requestFullScreen() {
        var docEl = window.document.documentElement;
        var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
        
        if (requestFullScreen) {
            requestFullScreen.call(docEl);
        }
    }
</script>
