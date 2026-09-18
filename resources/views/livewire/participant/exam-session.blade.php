<x-slot name="header">
    <div class="flex justify-between items-center w-full">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            {{ $exam->title }}
        </h2>
        <div class="flex items-center space-x-4">
            <div id="exam-timer" class="text-lg md:text-xl font-mono font-bold px-4 py-1.5 bg-gray-100 rounded-lg shadow-inner border border-gray-200 text-gray-700 transition-colors">
                00:00:00
            </div>
        </div>
    </div>
</x-slot>

<!-- Single Root Element for Livewire -->
<div wire:poll.7s="checkStatus" x-data="{
    isFullscreen: false,
    violationCount: 0,
    isAlerting: false,
    
    // Add hidden div to hold the latest endTimeFormatted updated by Livewire
    
    
    initFullscreen() {
        this.checkFullscreen();
        
        document.addEventListener('fullscreenchange', () => this.handleFullscreenChange());
        document.addEventListener('webkitfullscreenchange', () => this.handleFullscreenChange());
        document.addEventListener('mozfullscreenchange', () => this.handleFullscreenChange());
        document.addEventListener('MSFullscreenChange', () => this.handleFullscreenChange());
        
        // Mencegah klik kanan
        document.addEventListener('contextmenu', event => {
            event.preventDefault();
            this.handleViolation('Klik kanan dinonaktifkan selama ujian!');
        });

        document.addEventListener('keydown', (e) => {
            // Block F12, Escape, Alt, Ctrl+C, Ctrl+V, dll
            if (e.key === 'F12' || e.key === 'Escape' || e.key === 'Alt' || e.key === 'Tab' || e.key === 'Meta' || e.key === 'OS' || (e.ctrlKey && ['c', 'v', 'p', 'x'].includes(e.key.toLowerCase()))) {
                if (e.key !== 'Escape') { 
                    this.handleViolation('Kombinasi tombol dilarang (seperti Alt, Ctrl, F12) ditekan selama ujian!');
                }
            }
        });

        window.addEventListener('blur', () => {
            this.handleViolation('Anda terdeteksi meninggalkan halaman ujian (pindah tab atau aplikasi lain)!');
        });
        
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.handleViolation('Halaman ujian ditutup atau disembunyikan!');
            }
        });
    },

    handleFullscreenChange() {
        const isFs = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
        if (!isFs && this.isFullscreen) {
            this.handleViolation('Anda keluar dari mode layar penuh!');
        }
        this.isFullscreen = isFs;
    },

    handleViolation(message) {
        if (this.isAlerting) return;
        this.isAlerting = true;
        
        this.isFullscreen = false; 
        this.violationCount++;
        
        // Record violation to backend
        this.$wire.recordViolation(message);
        
        if (document.exitFullscreen && document.fullscreenElement) {
            document.exitFullscreen().catch(e => {});
        }

        alert('PERINGATAN PELANGGARAN (' + this.violationCount + 'x)\n\n' + message + '\n\nSistem mencatat aktivitas mencurigakan ini.');
        
        setTimeout(() => { this.isAlerting = false; }, 1000);
    },
    
    checkFullscreen() {
        this.isFullscreen = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
    },
    
    requestFullscreen() {
        const elem = document.documentElement;
        if (elem.requestFullscreen) { elem.requestFullscreen(); } 
        else if (elem.webkitRequestFullscreen) { elem.webkitRequestFullscreen(); } 
        else if (elem.msRequestFullscreen) { elem.msRequestFullscreen(); }
        
        setTimeout(() => { this.checkFullscreen(); }, 300);
    },

    initTimer() {
        let localEndTime = null;
        let lastRemaining = null;

        const updateTimer = () => {
            const timerEl = document.getElementById('exam-timer');
            if (!timerEl) return;
            
            if (document.getElementById('paused-overlay')) {
                timerEl.innerHTML = 'DIJEDA';
                timerEl.classList.remove('bg-gray-100', 'text-gray-700', 'bg-red-50', 'text-red-600', 'animate-pulse');
                timerEl.classList.add('bg-yellow-100', 'text-yellow-700');
                return;
            }
            
            const dataEl = document.getElementById('livewire-remaining-time');
            if (dataEl && dataEl.dataset.remaining) {
                const currentRemaining = parseInt(dataEl.dataset.remaining);
                
                if (localEndTime === null) {
                    localEndTime = new Date().getTime() + (currentRemaining * 1000);
                    lastRemaining = currentRemaining;
                } else {
                    const currentLocalRemaining = (localEndTime - new Date().getTime()) / 1000;
                    // Resync local end time if drift is more than 3 seconds (e.g. after pause/resume or network delay)
                    if (Math.abs(currentLocalRemaining - currentRemaining) > 3) {
                        localEndTime = new Date().getTime() + (currentRemaining * 1000);
                        lastRemaining = currentRemaining;
                    }
                }
            }
            
            if (!localEndTime) return;

            const now = new Date().getTime();
            const distance = localEndTime - now;
            
            if (distance < 0) {
                timerEl.innerHTML = 'WAKTU HABIS';
                timerEl.classList.add('bg-red-100', 'text-red-700', 'border-red-200');
                timerEl.classList.remove('bg-gray-100', 'text-gray-700');
                
                this.$wire.finishExam();
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            timerEl.innerHTML = 
                (hours < 10 ? '0' + hours : hours) + ':' + 
                (minutes < 10 ? '0' + minutes : minutes) + ':' + 
                (seconds < 10 ? '0' + seconds : seconds);
                
            if (distance < 5 * 60 * 1000) {
                timerEl.classList.add('bg-red-50', 'text-red-600', 'border-red-200', 'animate-pulse');
                timerEl.classList.remove('bg-gray-100', 'text-gray-700');
            }
        };
        
        setInterval(updateTimer, 1000);
        
        Livewire.hook('message.processed', () => {
            updateTimer();
        });
        
        updateTimer();
    }
}" x-init="initFullscreen(); initTimer();" class="select-none relative">
    
    <!-- Hidden element inside livewire root to keep track of updated remaining time -->
    <div id="livewire-remaining-time" class="hidden" data-remaining="{{ $remainingSeconds }}"></div>

    @if($session->is_paused)
        <div id="paused-overlay" class="fixed inset-0 z-[100] bg-gray-900 bg-opacity-95 flex flex-col items-center justify-center p-6 text-center">
            <svg class="w-32 h-32 text-yellow-500 mb-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">UJIAN SEDANG DIJEDA</h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-2xl">Harap tunggu instruksi selanjutnya dari panitia. Jangan tutup browser Anda.</p>
            <div class="mt-8 px-6 py-3 bg-gray-800 rounded-full border border-gray-700 shadow-inner">
                <p class="text-green-400 font-bold">Waktu ujian Anda aman dan tidak akan berkurang.</p>
            </div>
        </div>
    @endif

    <div class="w-full py-6 px-4 sm:px-6 lg:px-10 max-w-full mx-auto flex flex-col md:flex-row gap-8 items-start">
    
    <!-- Main Content: Current Question -->
    <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 relative min-h-[500px] flex flex-col">
        
        @if($currentQuestion)
            <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-100">
                <div class="flex flex-col">
                    <h3 class="text-lg font-bold text-gray-800">
                        Soal No. {{ $currentQuestionIndex + 1 }}
                    </h3>
                    @if($currentQuestion->category)
                        <div class="mt-1">
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-md border border-blue-200">
                                {{ $currentQuestion->category->name }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="flex space-x-3 items-center">
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">{{ $currentQuestion->points }} Poin</span>
                    
                    <button wire:click="toggleDoubtful({{ $currentQuestion->id }})" class="flex items-center text-sm font-medium px-3 py-1 rounded-full border transition-colors {{ ($doubtful[$currentQuestion->id] ?? false) ? 'bg-yellow-100 text-yellow-800 border-yellow-200 hover:bg-yellow-200' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                        {{ ($doubtful[$currentQuestion->id] ?? false) ? 'Ditandai Ragu-ragu' : 'Tandai Ragu-ragu' }}
                    </button>
                </div>
            </div>

            <div class="prose max-w-none text-gray-800 text-lg md:text-xl mb-8 leading-relaxed">
                {!! nl2br(e($currentQuestion->text)) !!}
            </div>

            <div class="space-y-3 flex-1">
                @if($currentQuestion->type === 'multiple_choice')
                    @foreach($currentOptions as $option)
                        <label wire:key="label_opt_{{ $currentQuestion->id }}_{{ $option->id }}" class="relative flex items-start p-4 cursor-pointer rounded-xl border transition-all {{ ($answers[$currentQuestion->id] ?? null) == $option->id ? 'bg-blue-50/50 border-blue-400 shadow-sm ring-1 ring-blue-400' : 'bg-white border-gray-200 hover:bg-gray-50' }}">
                            <div class="flex items-center h-6">
                                <input type="radio" name="question_{{ $currentQuestion->id }}" id="opt_{{ $option->id }}" wire:model.live="answers.{{ $currentQuestion->id }}" value="{{ $option->id }}" class="w-5 h-5 text-blue-600 bg-white border-gray-300 focus:ring-blue-500 mt-0.5">
                            </div>
                            <div class="ml-4 flex-1">
                                <span class="block text-gray-900 font-medium text-lg">{{ $option->text }}</span>
                            </div>
                        </label>
                    @endforeach
                @else
                    <textarea wire:model.live.debounce.1000ms="answers.{{ $currentQuestion->id }}" rows="6" class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-4 shadow-sm" placeholder="Ketik jawaban Anda di sini..."></textarea>
                @endif
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center">
                <button wire:click="previousQuestion" @if($currentQuestionIndex === 0) disabled @endif class="px-5 py-2.5 rounded-xl font-medium flex items-center transition-all {{ $currentQuestionIndex === 0 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Sebelumnya
                </button>
                
                @if($currentQuestionIndex < $totalQuestions - 1)
                    <button wire:click="nextQuestion" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 shadow-sm transition-all flex items-center">
                        Selanjutnya
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                @else
                    <button wire:click="showSummary" class="px-5 py-2.5 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 shadow-sm transition-all flex items-center">
                        Selesai
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                @endif
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center text-gray-500">
                <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>Menyiapkan soal ujian...</p>
            </div>
        @endif
    </div>

    <!-- Sidebar: Grid Navigation -->
    <div class="w-full md:w-96 shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Navigasi Soal
            </h4>
            
            <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-5 gap-2 mb-6">
                @foreach($questionOrder as $index => $qId)
                    @php
                        $isAnswered = !empty($answers[$qId]);
                        $isDoubtful = $doubtful[$qId] ?? false;
                        $isActive = $currentQuestionIndex === $index;
                        
                        $btnClass = 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'; // Default (Unanswered)
                        
                        if ($isActive) {
                            $btnClass = 'bg-blue-600 border-blue-600 text-white ring-2 ring-blue-200 ring-offset-1';
                        } elseif ($isDoubtful) {
                            $btnClass = 'bg-yellow-400 border-yellow-500 text-yellow-900 font-bold shadow-inner';
                        } elseif ($isAnswered) {
                            $btnClass = 'bg-green-500 border-green-600 text-white shadow-inner';
                        }
                    @endphp
                    <button wire:click="goToQuestion({{ $index }})" class="w-12 h-12 flex items-center justify-center rounded-xl border text-base font-bold transition-all {{ $btnClass }}">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>

            <!-- Legend -->
            <div class="border-t border-gray-100 pt-5 space-y-3 text-sm font-medium text-gray-600">
                <div class="flex items-center"><span class="w-5 h-5 bg-white border border-gray-300 rounded mr-3"></span> Belum Dijawab</div>
                <div class="flex items-center"><span class="w-5 h-5 bg-green-500 rounded mr-3"></span> Sudah Dijawab</div>
                <div class="flex items-center"><span class="w-5 h-5 bg-yellow-400 rounded mr-3"></span> Ragu-ragu</div>
                <div class="flex items-center"><span class="w-5 h-5 bg-blue-600 rounded mr-3"></span> Sedang Aktif</div>
            </div>
        </div>
    </div>

    <!-- Summary Modal -->
    @if($showSummaryModal)
        <div class="fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 flex flex-col items-center text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-50 mb-4">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <h3 class="text-2xl leading-6 font-extrabold text-gray-900 mb-2" id="modal-title">
                            Konfirmasi Selesai
                        </h3>
                        <p class="text-sm text-gray-500 mb-6 px-2">
                            Apakah Anda yakin ingin mengumpulkan jawaban dan mengakhiri ujian? Pastikan semua soal telah terjawab.
                        </p>
                        
                        <div class="w-full bg-gray-50 rounded-2xl border border-gray-100 p-5 space-y-4 shadow-inner">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600 font-semibold text-sm uppercase tracking-wider">Total Soal</span>
                                <span class="font-black text-gray-900 text-xl">{{ $summaryData['total'] }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600 font-semibold text-sm uppercase tracking-wider">Sudah Dijawab</span>
                                <span class="font-black text-green-600 text-xl">{{ $summaryData['answered'] }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600 font-semibold text-sm uppercase tracking-wider">Belum Dijawab</span>
                                <span class="font-black text-xl {{ $summaryData['unanswered'] > 0 ? 'text-red-600' : 'text-gray-400' }}">
                                    {{ $summaryData['unanswered'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-semibold text-sm uppercase tracking-wider">Ragu-ragu</span>
                                <span class="font-black text-xl {{ $summaryData['doubtful'] > 0 ? 'text-yellow-600' : 'text-gray-400' }}">
                                    {{ $summaryData['doubtful'] }}
                                </span>
                            </div>
                        </div>

                        @if($summaryData['unanswered'] > 0 || $summaryData['doubtful'] > 0)
                            <div class="mt-5 w-full bg-yellow-50 border border-yellow-200 p-4 rounded-xl text-yellow-800 text-sm font-medium flex items-center justify-center text-center">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <span>Ada soal yang <strong>belum dijawab</strong> atau <strong>ragu-ragu</strong>.</span>
                            </div>
                        @endif
                    </div>
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-100 gap-3">
                        <button type="button" wire:click="finishExam" class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-md px-6 py-3 bg-blue-600 text-base font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
                            Kirim Jawaban
                        </button>
                        <button type="button" wire:click="closeSummary" class="mt-3 sm:mt-0 w-full inline-flex justify-center items-center rounded-xl border-2 border-gray-200 shadow-sm px-6 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-all">
                            Kembali Periksa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Fullscreen Warning Overlay -->
    <div x-show="!isFullscreen" x-cloak class="fixed inset-0 z-[100] bg-gray-900 flex flex-col items-center justify-center p-6 text-center">
        <svg class="w-24 h-24 text-red-500 mb-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <h2 class="text-3xl font-extrabold text-white mb-4">Ujian Membutuhkan Layar Penuh</h2>
        <p class="text-lg text-gray-300 max-w-2xl mb-8 leading-relaxed">
            Demi menjaga integritas ujian, sistem mengharuskan Anda untuk berada pada mode <strong>Layar Penuh (Full Screen)</strong>. <br>
            Sistem akan otomatis mendeteksi jika Anda keluar dari layar penuh, membuka tab lain, atau meminimalkan browser.
        </p>
        <button @click="requestFullscreen" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:shadow-blue-900/50 transition-all flex items-center transform hover:scale-105 border border-blue-500">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            Masuk ke Layar Penuh & Lanjutkan Ujian
        </button>
    </div>

    <!-- Offline Connection Warning -->
    <div wire:offline class="fixed top-0 left-0 right-0 z-[150] bg-red-600 text-white px-4 py-3 shadow-lg flex items-center justify-center animate-bounce">
        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span class="font-bold text-lg">KONEKSI INTERNET TERPUTUS!</span>
        <span class="ml-2">Jawaban Anda tidak dapat disimpan hingga koneksi kembali stabil.</span>
    </div>

<style>
    /* Menyembunyikan elemen sebelum alpine dimuat */
    [x-cloak] { display: none !important; }
    /* Mencegah user menyeleksi dan menyalin teks soal */
    .select-none {
        -webkit-user-select: none; /* Safari */
        -ms-user-select: none; /* IE 10+ */
        user-select: none;
    }
</style>
</div>
