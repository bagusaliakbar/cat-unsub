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
                                        
                                        @if($isCompleted)
                                            @if($exam->is_simulation)
                                                <button wire:click="retakeSimulation({{ $exam->id }})" wire:confirm="Anda akan mengulang ujian simulasi ini dari awal. Nilai sebelumnya akan dihapus. Lanjutkan?" type="button" class="w-full sm:w-auto bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-xl font-bold shadow-md hover:shadow-lg focus:ring-4 focus:ring-purple-300 transition-all transform hover:-translate-y-0.5 flex items-center justify-center shrink-0">
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
                                                <button wire:click="startExam({{ $exam->id }})" type="button" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-bold shadow-md hover:shadow-lg focus:ring-4 focus:ring-amber-300 transition-all transform hover:-translate-y-0.5 flex items-center justify-center shrink-0">
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
                                                <button wire:click="showRules({{ $exam->id }})" type="button" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-bold shadow-md hover:shadow-lg focus:ring-4 focus:ring-green-300 transition-all transform hover:-translate-y-0.5 flex items-center justify-center shrink-0">
                                                    Mulai Kerjakan
                                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Tata Tertib -->
            @if($confirmingExam)
            <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="cancelStart"></div>
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden relative z-10 flex flex-col max-h-[90vh] border border-gray-100">
                    <div class="bg-blue-600 px-6 py-5 flex justify-between items-center shrink-0">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tata Tertib Ujian
                        </h3>
                        <button wire:click="cancelStart" class="text-blue-100 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="p-6 md:p-8 overflow-y-auto">
                        <div class="mb-5">
                            <h4 class="text-xl font-bold text-gray-900">{{ $confirmingExam->title }}</h4>
                            <p class="text-sm text-gray-500 mt-1">Silakan baca tata tertib berikut dengan saksama sebelum memulai ujian.</p>
                        </div>
                        
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 text-gray-700 text-sm prose prose-sm max-w-none">
                            @if($confirmingExam->rules)
                                {!! nl2br(e($confirmingExam->rules)) !!}
                            @else
                                <div class="text-xs font-bold text-gray-500 mb-4 uppercase tracking-wide flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Tata Tertib Standar Sistem
                                </div>
                                <ul class="text-sm text-gray-700 space-y-4 list-none p-0 m-0">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>Berdoalah sebelum mulai mengerjakan soal ujian.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>Waktu akan <span class="font-bold text-gray-900">berjalan secara otomatis</span> setelah Anda menekan tombol mulai ujian.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        <span>Dilarang membuka <i>tab</i> browser lain, menutup browser, atau membuka aplikasi lain selama ujian berlangsung. (Sistem akan mendeteksi pelanggaran).</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        <span>Dilarang keras melakukan kecurangan dalam bentuk apapun, termasuk bekerjasama atau menggunakan alat bantu pencarian.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <span>Apabila waktu ujian habis, sistem akan <span class="font-bold text-gray-900">mengumpulkan jawaban Anda secara otomatis</span>.</span>
                                    </li>
                                </ul>
                            @endif
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
                        <button onclick="requestFullScreen()" wire:click="startExam({{ $confirmingExam->id }})" type="button" class="w-full sm:w-auto px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                            Saya Mengerti & Mulai
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
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
