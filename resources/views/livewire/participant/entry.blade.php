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
                                <span class="block text-gray-500 text-xs uppercase tracking-wider font-bold mb-1">Instansi</span>
                                <span class="font-medium text-gray-900 text-lg">{{ $participant->institution ?? '-' }}</span>
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
                                <span class="inline-block text-[11px] font-black px-3.5 py-1 rounded-full uppercase tracking-wider shadow-sm mb-2.5" style="background-color: #f59e0b !important; color: #0f172a !important;">
                                    SELEKSI BERBASIS COMPUTER ASSISTED TEST (CAT)
                                </span>
                                <h3 class="text-2xl sm:text-3xl font-black tracking-tight uppercase" style="color: #ffffff !important;">
                                    TATA TERTIB PESERTA
                                </h3>
                                <p class="text-xs sm:text-sm mt-1 font-medium" style="color: #bfdbfe !important;">
                                    {{ $viewingRulesExam->title }} — Harap dibaca dan dipahami sebelum memulai ujian.
                                </p>
                            </div>
                            
                            <div class="flex items-center gap-3 shrink-0">
                                <div class="hidden md:flex items-center gap-2.5 px-3.5 py-2 rounded-2xl border border-white/20 text-right" style="background-color: rgba(255, 255, 255, 0.12) !important;">
                                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <div>
                                        <div class="text-[11px] font-black text-amber-400 tracking-wider">CAT SYSTEM</div>
                                        <div class="text-[10px]" style="color: #bfdbfe !important;">UNSUB</div>
                                    </div>
                                </div>
                                <button wire:click="closeRules" type="button" class="p-2.5 rounded-xl transition-all hover:bg-white/20" style="color: #ffffff !important; background-color: rgba(255, 255, 255, 0.15) !important;">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Body: Grid 9 Kartu Infografis -->
                    <div class="p-4 sm:p-6 md:p-7 space-y-4 sm:space-y-5 bg-slate-100/70">

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

                        <!-- Grid 9 Kartu Infografis -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
                            
                            <!-- 1. Ketentuan Sebelum Tes (Biru) -->
                            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden flex flex-col h-full hover:shadow transition-shadow">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50/70 px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-7 h-7 rounded-full bg-blue-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm">1</span>
                                        <h4 class="font-bold text-blue-950 text-sm sm:text-base">Ketentuan Sebelum Tes</h4>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1">
                                    <ul class="text-xs sm:text-[13px] text-slate-600 space-y-2.5 leading-relaxed list-disc list-outside pl-4 marker:text-blue-500">
                                        <li>Peserta hadir di lokasi ujian paling lambat <strong class="text-slate-900 font-bold">15 (lima belas) menit</strong> sebelum waktu tes resmi dimulai, sesuai dengan jadwal yang ditetapkan.</li>
                                        <li>Peserta wajib membawa <strong class="text-slate-900 font-bold">dokumen identitas diri yang sah</strong> (KTP/identitas resmi) untuk keperluan verifikasi.</li>
                                        <li>Peserta melakukan registrasi kehadiran pada daftar hadir yang disediakan oleh petugas.</li>
                                        <li>Peserta memahami dan menyiapkan diri untuk menggunakan <strong class="text-slate-900 font-bold">token akses unik</strong> yang dibagikan oleh Operator CAT.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- 2. Ketentuan Saat Tes (Oranye) -->
                            <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden flex flex-col h-full hover:shadow transition-shadow">
                                <div class="bg-gradient-to-r from-amber-50 to-orange-50/70 px-4 py-3 border-b border-amber-100 flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-7 h-7 rounded-full bg-amber-500 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm">2</span>
                                        <h4 class="font-bold text-amber-950 text-sm sm:text-base">Ketentuan Saat Tes</h4>
                                    </div>
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1">
                                    <ul class="text-xs sm:text-[13px] text-slate-600 space-y-2.5 leading-relaxed list-disc list-outside pl-4 marker:text-amber-500">
                                        <li>Peserta mengerjakan seluruh soal tes secara mandiri sesuai dengan instruksi sistem dan arahan Pengawas.</li>
                                        <li>Peserta <strong class="text-rose-600 font-bold">dilarang keras melakukan perpindahan tab (tab switching)</strong> atau membuka aplikasi lain di luar sistem CAT selama ujian berlangsung. Pelanggaran terekam otomatis oleh sistem.</li>
                                        <li>Peserta wajib menjaga ketertiban di dalam ruang ujian dan tidak melakukan tindakan yang mengganggu peserta lain.</li>
                                        <li>Segera lapor ke Pengawas jika terjadi kendala teknis; <strong class="text-slate-900 font-bold">tidak diperkenankan memperbaiki sistem sendiri</strong>.</li>
                                        <li>Jika waktu habis, sistem otomatis menghentikan sesi dan menyimpan seluruh jawaban yang telah dikerjakan.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- 3. Ketentuan Keterlambatan (Oranye) -->
                            <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden flex flex-col h-full hover:shadow transition-shadow">
                                <div class="bg-gradient-to-r from-amber-50 to-orange-50/70 px-4 py-3 border-b border-amber-100 flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-7 h-7 rounded-full bg-amber-500 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm">3</span>
                                        <h4 class="font-bold text-amber-950 text-sm sm:text-base">Ketentuan Keterlambatan</h4>
                                    </div>
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1">
                                    <ul class="text-xs sm:text-[13px] text-slate-600 space-y-2.5 leading-relaxed list-disc list-outside pl-4 marker:text-amber-500">
                                        <li>Peserta yang terlambat tetap diperkenankan masuk setelah melapor kepada Petugas Administrasi dan dicatat waktu kedatangannya.</li>
                                        <li>Waktu ujian pada sistem tetap berjalan sesuai jadwal serentak: <strong class="text-slate-900 font-bold">durasi pengerjaan akan berkurang otomatis tanpa penambahan waktu</strong>.</li>
                                        <li>Kebijakan keikutsertaan lebih lanjut ditetapkan oleh penyelenggara.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- 4. Penanganan Gangguan Teknis (Biru) -->
                            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden flex flex-col h-full hover:shadow transition-shadow">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50/70 px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-7 h-7 rounded-full bg-blue-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm">4</span>
                                        <h4 class="font-bold text-blue-950 text-sm sm:text-base">Penanganan Gangguan Teknis</h4>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1">
                                    <ul class="text-xs sm:text-[13px] text-slate-600 space-y-2.5 leading-relaxed list-disc list-outside pl-4 marker:text-blue-500">
                                        <li>Apabila terjadi gangguan teknis individual (PC bermasalah, mati lampu lokal) maupun massal, kegiatan dapat <strong class="text-blue-700 font-bold">di-pause (dijeda)</strong> atau disesuaikan oleh Tim Pelaksana.</li>
                                        <li><strong class="text-emerald-700 font-bold">Jawaban peserta dijamin aman</strong>, tersimpan secara <em>real-time</em> di server, dan tidak akan hilang sehingga ujian dapat dilanjutkan kembali setelah gangguan teratasi.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- 5. Ketentuan Setelah Tes (Biru) -->
                            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden flex flex-col h-full hover:shadow transition-shadow">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50/70 px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-7 h-7 rounded-full bg-blue-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm">5</span>
                                        <h4 class="font-bold text-blue-950 text-sm sm:text-base">Ketentuan Setelah Tes</h4>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1">
                                    <ul class="text-xs sm:text-[13px] text-slate-600 space-y-2.5 leading-relaxed list-disc list-outside pl-4 marker:text-blue-500">
                                        <li>Peserta memastikan telah menyelesaikan ujian dan mengamati perolehan <strong class="text-slate-900 font-bold">skor akhir secara instan</strong> di layar.</li>
                                        <li>Peserta meninggalkan ruang ujian secara tertib setelah dipersilakan oleh Pengawas.</li>
                                        <li>Peserta menandatangani dokumen administrasi atau daftar hadir akhir sebelum meninggalkan lokasi ujian.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- 6. Barang yang Diperbolehkan & Dilarang (Oranye) -->
                            <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden flex flex-col h-full hover:shadow transition-shadow">
                                <div class="bg-gradient-to-r from-amber-50 to-orange-50/70 px-4 py-3 border-b border-amber-100 flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-7 h-7 rounded-full bg-amber-500 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm">6</span>
                                        <h4 class="font-bold text-amber-950 text-sm sm:text-base">Barang yang Diperbolehkan & Dilarang</h4>
                                    </div>
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 space-y-3">
                                    <!-- Diperbolehkan -->
                                    <div class="bg-emerald-50/90 border border-emerald-200 rounded-xl p-3.5 shadow-xs">
                                        <div class="flex items-center gap-1.5 text-emerald-800 text-xs font-black mb-1">
                                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            <span>Barang yang Diperbolehkan:</span>
                                        </div>
                                        <p class="text-xs sm:text-[13px] text-emerald-950 leading-relaxed pl-5">
                                            Alat tulis atau perlengkapan pribadi pendukung sesuai dengan ketentuan spesifik kegiatan.
                                        </p>
                                    </div>
                                    <!-- Dilarang -->
                                    <div class="bg-rose-50/90 border border-rose-200 rounded-xl p-3.5 shadow-xs">
                                        <div class="flex items-center gap-1.5 text-rose-800 text-xs font-black mb-1">
                                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            <span>Barang yang Dilarang:</span>
                                        </div>
                                        <p class="text-xs sm:text-[13px] text-rose-950 leading-relaxed pl-5">
                                            Telepon genggam (smartphone), buku catatan, kalkulator, earphone/headset, alat komunikasi lain, atau perangkat elektronik di luar komputer ujian, kecuali ditetapkan lain oleh penyelenggara.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- 7. Larangan Khusus (Oranye) -->
                            <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden flex flex-col h-full hover:shadow transition-shadow">
                                <div class="bg-gradient-to-r from-amber-50 to-orange-50/70 px-4 py-3 border-b border-amber-100 flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-7 h-7 rounded-full bg-amber-500 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm">7</span>
                                        <h4 class="font-bold text-amber-950 text-sm sm:text-base">Larangan Khusus</h4>
                                    </div>
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1">
                                    <ul class="text-xs sm:text-[13px] text-slate-600 space-y-2.5 leading-relaxed list-disc list-outside pl-4 marker:text-amber-500">
                                        <li>Peserta <strong class="text-rose-600 font-bold">dilarang bekerja sama</strong> dengan peserta lain dalam bentuk apa pun selama ujian berlangsung.</li>
                                        <li>Peserta <strong class="text-rose-600 font-bold">dilarang keras mengakses sumber informasi eksternal</strong> (buku, catatan fisik, pencarian internet di luar aplikasi CAT, atau meminta bantuan pihak lain).</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- 8. Sifat Hasil Tes CAT (Biru) -->
                            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden flex flex-col h-full hover:shadow transition-shadow">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50/70 px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="w-7 h-7 rounded-full bg-blue-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm">8</span>
                                        <h4 class="font-bold text-blue-950 text-sm sm:text-base">Sifat Hasil Tes CAT</h4>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="p-4 sm:p-5 flex-1 flex items-center">
                                    <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed">
                                        Perolehan nilai atau hasil tes CAT <strong class="text-slate-900 font-bold">bukan merupakan penentu tunggal kelulusan akhir</strong>, melainkan digunakan sebagai salah satu instrumen ukur dan komponen penilaian sah sesuai ketentuan peraturan perundang-undangan serta kebijakan penyelenggaraan kegiatan.
                                    </p>
                                </div>
                            </div>

                            <!-- 9. Sanksi Pelanggaran (Full Width Banner) -->
                            <div class="col-span-1 md:col-span-2 rounded-2xl border border-blue-900 shadow-md overflow-hidden text-white" style="background: linear-gradient(135deg, #091a32 0%, #0f2b52 50%, #16244f 100%) !important; color: #ffffff !important;">
                                <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                    <div class="flex items-center space-x-3 shrink-0 sm:border-r sm:border-blue-800/80 sm:pr-5">
                                        <span class="w-8 h-8 rounded-full bg-amber-500 text-slate-950 font-black text-sm flex items-center justify-center shrink-0 shadow">9</span>
                                        <div>
                                            <h4 class="font-black text-white text-base tracking-wide uppercase">Sanksi Pelanggaran</h4>
                                            <span class="text-[11px] font-extrabold text-amber-400 uppercase tracking-widest">Tindakan Tegas</span>
                                        </div>
                                    </div>
                                    <div class="text-xs sm:text-sm text-blue-100 leading-relaxed flex-1">
                                        Pelanggaran terhadap tata tertib ini—termasuk pelanggaran keamanan sistem yang terekam secara otomatis oleh log CAT—akan dicatat dan dilaporkan untuk ditindaklanjuti dengan <strong class="text-amber-400 font-bold underline underline-offset-2">sanksi tegas</strong> sesuai dengan ketentuan kebijakan penyelenggara kegiatan.
                                    </div>
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
