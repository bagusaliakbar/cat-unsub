<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Ujian
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-10">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <div class="flex items-center text-sm text-gray-600 font-medium space-x-2 mb-6">
                <span class="text-blue-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Peserta
                </span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-900 font-bold text-base">Dashboard Ujian</span>
            </div>

        @if($exams->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center max-w-3xl mx-auto transform transition duration-500 hover:shadow-md hover:-translate-y-1">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></circle>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Ujian</h3>
                <p class="text-gray-500">Saat ini tidak ada jadwal ujian yang aktif atau tersedia untuk Anda. Silakan hubungi panitia jika Anda merasa ini adalah sebuah kesalahan.</p>
            </div>
        @else
            <!-- Exam Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($exams as $exam)
                    @php
                        $session = $userSessions[$exam->id] ?? null;
                        $status = $session ? $session->status : 'available';
                    @endphp
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col transform transition duration-300 hover:shadow-xl hover:-translate-y-1 group">
                        
                        <!-- Card Header Pattern -->
                        <div class="h-24 bg-gradient-to-br from-blue-700 to-blue-900 relative overflow-hidden">
                            <!-- Decorative circles -->
                            <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white opacity-10"></div>
                            <div class="absolute -bottom-8 -left-8 w-24 h-24 rounded-full bg-white opacity-10"></div>
                            
                            <div class="absolute inset-0 flex items-center px-6">
                                <h3 class="text-xl font-bold text-white drop-shadow-sm line-clamp-1">{{ $exam->title }}</h3>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-grow flex flex-col">
                            @if($exam->description)
                                <p class="text-sm text-gray-600 mb-6 line-clamp-2 leading-relaxed">
                                    {{ $exam->description }}
                                </p>
                            @endif
                            
                            <div class="grid grid-cols-2 gap-4 mb-6 mt-auto">
                                <div class="bg-gray-50 rounded-2xl p-4 text-center border border-gray-100 transition group-hover:border-blue-100 group-hover:bg-blue-50/30">
                                    <svg class="w-6 h-6 mx-auto text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div class="text-xs text-gray-500 font-medium">Durasi</div>
                                    <div class="text-lg font-bold text-gray-900">{{ $exam->duration_minutes }} <span class="text-sm font-medium text-gray-600">Menit</span></div>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4 text-center border border-gray-100 transition group-hover:border-blue-100 group-hover:bg-blue-50/30">
                                    <svg class="w-6 h-6 mx-auto text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    <div class="text-xs text-gray-500 font-medium">Jumlah Soal</div>
                                    <div class="text-lg font-bold text-gray-900">{{ $exam->total_questions }}</div>
                                </div>
                            </div>

                            <!-- Action Section -->
                            <div class="mt-auto" x-data="{ openRules: false, agreed: false }">
                                @if($status === 'completed')
                                    <div class="w-full bg-green-50 text-green-700 font-bold py-3 px-4 rounded-xl flex items-center justify-center border border-green-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Selesai Dikerjakan
                                    </div>
                                @elseif($status === 'started')
                                    <button @click="
                                                const elem = document.documentElement;
                                                if (elem.requestFullscreen) { elem.requestFullscreen(); }
                                                else if (elem.webkitRequestFullscreen) { elem.webkitRequestFullscreen(); }
                                                else if (elem.msRequestFullscreen) { elem.msRequestFullscreen(); }
                                            " 
                                            wire:click="startExam({{ $exam->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full bg-yellow-500 hover:bg-yellow-600 disabled:opacity-50 text-white font-bold py-3 px-4 rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center transform hover:-translate-y-0.5">
                                        Lanjutkan Ujian
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </button>
                                @else
                                    <button @click="openRules = true" class="w-full bg-gradient-to-r from-blue-700 to-blue-800 hover:from-blue-800 hover:to-blue-900 text-white font-bold py-3 px-4 rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center transform hover:-translate-y-0.5">
                                        Mulai Ujian
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    </button>

                                    <!-- Rules Modal -->
                                    <template x-teleport="body">
                                        <div x-show="openRules" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                
                                                <div x-show="openRules" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="openRules = false"></div>

                                                <!-- This element is to trick the browser into centering the modal contents. -->
                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                                <div x-show="openRules" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                                                    
                                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-6">
                                                        <div class="text-center">
                                                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-50 mb-4">
                                                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                            </div>
                                                            <h3 class="text-2xl font-bold text-gray-900" id="modal-title">
                                                                Tata Tertib Ujian
                                                            </h3>
                                                            <p class="text-blue-600 font-semibold mt-1">{{ $exam->title }}</p>
                                                        </div>

                                                        <div class="mt-6 bg-gray-50 rounded-xl p-6 border border-gray-100 text-left">
                                                            <ul class="text-sm text-gray-700 space-y-4 list-none">
                                                                @if(!empty($exam->rules))
                                                                    @foreach(explode("\n", $exam->rules) as $rule)
                                                                        @if(trim($rule) !== '')
                                                                        <li class="flex items-start">
                                                                            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                            <span>{!! nl2br(e(trim($rule))) !!}</span>
                                                                        </li>
                                                                        @endif
                                                                    @endforeach
                                                                @else
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
                                                                        <span>Apabila waktu ujian (<span class="font-bold text-gray-900">{{ $exam->duration_minutes }} Menit</span>) habis, sistem akan <span class="font-bold text-gray-900">mengumpulkan jawaban Anda secara otomatis</span>.</span>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>

                                                        <!-- Token Section Inside Modal -->
                                                        @if(!empty($exam->token))
                                                            <div class="mt-8 bg-blue-50/50 p-5 rounded-xl border border-blue-100">
                                                                <label for="token_{{ $exam->id }}" class="block text-sm font-bold text-blue-900 mb-2">Masukkan Token Ujian</label>
                                                                <input type="text" id="token_{{ $exam->id }}" wire:model="tokenInputs.{{ $exam->id }}" class="block w-full border-blue-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-3 bg-white" placeholder="Token dari pengawas...">
                                                                @if (session()->has('error_'.$exam->id))
                                                                    <span class="text-red-600 text-sm mt-2 block font-medium flex items-center">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                        {{ session('error_'.$exam->id) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endif

                                                        <div class="mt-6">
                                                            <label class="flex items-center cursor-pointer bg-gray-50 p-4 rounded-xl border border-gray-200 transition hover:bg-gray-100">
                                                                <input type="checkbox" x-model="agreed" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                                                <span class="ml-3 text-sm font-semibold text-gray-800">Saya telah membaca dan menyetujui tata tertib di atas.</span>
                                                            </label>
                                                        </div>

                                                    </div>
                                                    <div class="bg-gray-50 px-4 py-5 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                                        <button type="button" 
                                                                :disabled="!agreed" 
                                                                :class="agreed ? 'bg-blue-700 hover:bg-blue-800 focus:ring-blue-500' : 'bg-gray-300 cursor-not-allowed'" 
                                                                @click="
                                                                    if (agreed) {
                                                                        const elem = document.documentElement;
                                                                        if (elem.requestFullscreen) { elem.requestFullscreen(); }
                                                                        else if (elem.webkitRequestFullscreen) { elem.webkitRequestFullscreen(); }
                                                                        else if (elem.msRequestFullscreen) { elem.msRequestFullscreen(); }
                                                                    }
                                                                " 
                                                                wire:click="startExam({{ $exam->id }})"
                                                                wire:loading.attr="disabled"
                                                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3 text-base font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition disabled:opacity-50">
                                                            Mulai Kerjakan
                                                        </button>
                                                        <button type="button" @click="openRules = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                                                            Batal
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
