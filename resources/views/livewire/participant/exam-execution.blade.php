<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8" x-data="examTimer('{{ $endTimeFormatted }}', @this)">
    <div class="flex flex-col md:flex-row gap-6">
        
        <!-- Main Content Area: Question & Options -->
        <div class="w-full md:w-3/4">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                @if(count($questions) > 0)
                    @php 
                        $currentQuestion = $questions[$currentQuestionIndex]; 
                    @endphp

                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Soal No. {{ $currentQuestionIndex + 1 }}</h3>
                        <div class="text-sm text-gray-500">Poin: {{ $currentQuestion->points }}</div>
                    </div>

                    <!-- Question Text -->
                    <div class="text-lg text-gray-900 mb-6 min-h-[100px]">
                        {!! nl2br(e($currentQuestion->text)) !!}
                    </div>

                    <!-- Options -->
                    <div class="mb-8 space-y-4">
                        @if($currentQuestion->type === 'multiple_choice')
                            @foreach($currentQuestion->options as $option)
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition {{ (isset($answers[$currentQuestion->id]) && $answers[$currentQuestion->id] == $option->id) ? 'bg-blue-50 border-blue-500' : 'border-gray-200' }}">
                                    <input type="radio" 
                                           name="question_{{ $currentQuestion->id }}" 
                                           value="{{ $option->id }}" 
                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500"
                                           wire:model.live="answers.{{ $currentQuestion->id }}"
                                           wire:change="saveAnswer({{ $currentQuestion->id }}, {{ $option->id }}, 'multiple_choice')">
                                    <span class="ml-3 text-gray-800">{{ $option->text }}</span>
                                </label>
                            @endforeach
                        @else
                            <textarea 
                                wire:model.lazy="answers.{{ $currentQuestion->id }}"
                                wire:change="saveAnswer({{ $currentQuestion->id }}, $event.target.value, 'essay')"
                                class="w-full p-4 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 h-32" 
                                placeholder="Ketik jawaban Anda di sini..."></textarea>
                            <div class="text-xs text-gray-500 mt-1">Jawaban akan otomatis tersimpan saat Anda berpindah dari kotak teks.</div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center border-t pt-4">
                        <button wire:click="previousQuestion()" 
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none {{ $currentQuestionIndex == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                {{ $currentQuestionIndex == 0 ? 'disabled' : '' }}>
                            &larr; Sebelumnya
                        </button>

                        <button wire:click="toggleDoubtful({{ $currentQuestion->id }})" 
                                class="px-4 py-2 border rounded-md shadow-sm text-sm font-medium focus:outline-none transition {{ (isset($doubtful[$currentQuestion->id]) && $doubtful[$currentQuestion->id]) ? 'bg-yellow-100 border-yellow-500 text-yellow-800' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }}">
                            <input type="checkbox" class="mr-2" {{ (isset($doubtful[$currentQuestion->id]) && $doubtful[$currentQuestion->id]) ? 'checked' : '' }} onclick="return false;"> Ragu-ragu
                        </button>

                        <button wire:click="nextQuestion()" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none {{ $currentQuestionIndex == count($questions) - 1 ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                {{ $currentQuestionIndex == count($questions) - 1 ? 'disabled' : '' }}>
                            Selanjutnya &rarr;
                        </button>
                    </div>

                @else
                    <div class="text-center text-gray-500 py-10">
                        Tidak ada soal dalam ujian ini.
                    </div>
                @endif

            </div>
        </div>

        <!-- Sidebar Area: Timer & Navigation Grid -->
        <div class="w-full md:w-1/4 flex flex-col gap-6">
            
            <!-- Timer Card -->
            <div class="bg-white shadow-xl sm:rounded-lg p-6 text-center border-t-4 border-red-500">
                <h4 class="text-sm text-gray-500 uppercase tracking-wider mb-2 font-semibold">Sisa Waktu</h4>
                <div class="text-3xl font-mono font-bold" :class="timeRemaining <= 300 ? 'text-red-600 animate-pulse' : 'text-gray-800'" x-text="formattedTime">
                    --:--:--
                </div>
            </div>

            <!-- Navigation Grid -->
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <h4 class="text-sm text-gray-500 uppercase tracking-wider mb-4 font-semibold text-center">Navigasi Soal</h4>
                
                <div class="grid grid-cols-5 gap-2">
                    @foreach($questions as $index => $q)
                        @php
                            $isAnswered = isset($answers[$q->id]) && !empty($answers[$q->id]);
                            $isDoubtful = isset($doubtful[$q->id]) && $doubtful[$q->id];
                            $isCurrent = $currentQuestionIndex === $index;
                            
                            $bgClass = 'bg-white text-gray-700 border-gray-300'; // default
                            
                            if ($isCurrent) {
                                $bgClass = 'bg-blue-600 text-white border-blue-600';
                            } elseif ($isDoubtful) {
                                $bgClass = 'bg-yellow-400 text-yellow-900 border-yellow-500';
                            } elseif ($isAnswered) {
                                $bgClass = 'bg-green-500 text-white border-green-600';
                            }
                        @endphp
                        
                        <button wire:click="setQuestion({{ $index }})" 
                                class="w-10 h-10 flex items-center justify-center rounded border font-medium text-sm transition hover:opacity-80 {{ $bgClass }}">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
                
                <!-- Legend -->
                <div class="mt-6 space-y-2 text-xs text-gray-600">
                    <div class="flex items-center"><div class="w-3 h-3 rounded bg-white border border-gray-300 mr-2"></div> Belum Dijawab</div>
                    <div class="flex items-center"><div class="w-3 h-3 rounded bg-green-500 mr-2"></div> Sudah Dijawab</div>
                    <div class="flex items-center"><div class="w-3 h-3 rounded bg-yellow-400 mr-2"></div> Ragu-ragu</div>
                    <div class="flex items-center"><div class="w-3 h-3 rounded bg-blue-600 mr-2"></div> Sedang Aktif</div>
                </div>
            </div>

            <!-- Submit Button -->
            <button wire:click="submitExam()" 
                    wire:confirm="Apakah Anda yakin ingin menyelesaikan ujian ini? Anda tidak dapat kembali mengubah jawaban setelah ini." 
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded shadow-lg text-lg transition">
                Selesai Ujian
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('examTimer', (endTimeStr, livewireComponent) => ({
            endTime: new Date(endTimeStr).getTime(),
            timeRemaining: 0,
            formattedTime: '--:--:--',
            interval: null,

            init() {
                this.updateTimer();
                this.interval = setInterval(() => {
                    this.updateTimer();
                }, 1000);
            },

            updateTimer() {
                const now = new Date().getTime();
                const distance = this.endTime - now;

                if (distance < 0) {
                    clearInterval(this.interval);
                    this.timeRemaining = 0;
                    this.formattedTime = '00:00:00';
                    // Auto submit via livewire
                    livewireComponent.submitExam();
                    return;
                }

                this.timeRemaining = Math.floor(distance / 1000);

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                this.formattedTime = 
                    String(hours).padStart(2, '0') + ':' + 
                    String(minutes).padStart(2, '0') + ':' + 
                    String(seconds).padStart(2, '0');
            }
        }))
    })
</script>
