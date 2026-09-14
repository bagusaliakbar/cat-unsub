<x-slot name="header">
    <div class="flex justify-between items-center w-full">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            {{ $exam->title }} <span class="ml-3 px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full font-bold uppercase tracking-wider">Mode Preview</span>
        </h2>
        <div class="flex items-center space-x-4">
            <button wire:click="finishExam" class="text-sm font-bold bg-white text-gray-700 hover:text-red-600 border border-gray-200 hover:border-red-200 hover:bg-red-50 px-4 py-2 rounded-lg transition-colors flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Tutup Preview
            </button>
            <div class="text-lg md:text-xl font-mono font-bold px-4 py-1.5 bg-gray-100 rounded-lg shadow-inner border border-gray-200 text-gray-700">
                00:00:00
            </div>
        </div>
    </div>
</x-slot>

<div class="select-none">
    
    <!-- Banner Peringatan -->
    <div class="bg-purple-600 text-white text-center py-2 px-4 text-sm font-bold shadow-md relative z-10">
        <span class="flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            MODE PREVIEW: Jawaban yang Anda pilih tidak akan disimpan ke dalam sistem. Layout ini meniru tampilan asli yang akan dilihat oleh peserta.
        </span>
    </div>

    <div class="w-full py-6 px-4 sm:px-6 lg:px-10 max-w-full mx-auto flex flex-col md:flex-row gap-8 items-start">
    
    <!-- Main Content: Current Question -->
    <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 relative min-h-[500px] flex flex-col">
        
        @if($currentQuestion)
            <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">
                    Soal No. {{ $currentQuestionIndex + 1 }}
                </h3>
                <title>PREVIEW - {{ config('app.name', 'Laravel') }}</title>

                <!-- Favicon -->
                <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

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
                    <button wire:click="finishExam" wire:confirm="Keluar dari mode preview?" class="px-5 py-2.5 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 shadow-sm transition-all flex items-center">
                        Tutup Preview
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                @endif
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center text-gray-500">
                <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>Tidak ada soal pada ujian ini.</p>
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

</div>
