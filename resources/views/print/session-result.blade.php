<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - {{ $session->user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap');
        
        body { 
            font-family: 'Times New Roman', Times, serif; 
            background: #fff; 
            color: #000;
            line-height: 1.3;
        }
        @media print {
            @page { 
                margin: 0; 
                size: A4;
            }
            body { 
                background: #fff; 
                margin: 0; 
                padding: 1cm 2cm 2cm 2cm; 
            }
            .no-print { display: none !important; }
            .break-inside-avoid { break-inside: avoid; }
            .page-break { page-break-before: always; }
        }
        .kop-surat-border {
            border-bottom: 3px solid #000;
            position: relative;
        }
        .kop-surat-border::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -5px;
            border-bottom: 1px solid #000;
        }
        table.table-bordered th, table.table-bordered td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        table.table-bordered th {
            background-color: #203864 !important;
            color: white;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<body class="p-4 sm:p-8 max-w-4xl mx-auto text-black text-[11pt]" onload="window.print()">

    <!-- Header / Kop Surat -->
    <div class="mb-4 text-center">
        <img src="{{ asset('images/kop-unsub.jpg') }}" alt="Kop Universitas Subang" class="w-full mx-auto h-auto object-contain">
    </div>

    <!-- Title -->
    <div class="text-center mb-8">
        <h3 class="text-lg font-bold uppercase">DETAIL HASIL UJIAN PESERTA</h3>
        @if($report && $report->village)
        <h3 class="text-lg font-bold uppercase">CALON KEPALA DESA ANTAR WAKTU (PAW) DESA {{ strtoupper($report->village) }}</h3>
        <h3 class="text-lg font-bold uppercase">KECAMATAN {{ strtoupper($report->district) }} KABUPATEN SUBANG</h3>
        @endif
    </div>

    <!-- Info Peserta & Ujian -->
    <div class="flex justify-between mb-8">
        <table class="w-full text-left align-top">
            <tr>
                <td class="w-40 font-bold align-top">Nomor Peserta</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top uppercase">{{ $session->user->participant_number ?? $session->user->nik }}</td>
            </tr>
            <tr>
                <td class="w-40 font-bold align-top">Nama Peserta</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top uppercase">{{ $session->user->name }}</td>
            </tr>
            <tr>
                <td class="w-40 font-bold align-top">Ujian</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top uppercase">{{ ucwords(strtolower($session->exam->title)) }}</td>
            </tr>
            <tr>
                <td class="w-40 font-bold align-top">Waktu Pengerjaan</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top">
                    {{ \Carbon\Carbon::parse($session->started_at)->format('H:i:s') }} 
                    s.d 
                    {{ $session->completed_at ? \Carbon\Carbon::parse($session->completed_at)->format('H:i:s') : '-' }}
                </td>
            </tr>

            <tr>
                <td class="w-40 font-bold align-top pt-4">Skor Akhir</td>
                <td class="w-4 text-center align-top pt-4">:</td>
                <td class="align-top font-bold pt-4 {{ $session->score >= $session->exam->passing_grade ? 'text-green-600' : 'text-red-600' }}">
                    {{ round($session->score) }}
                </td>
            </tr>
        </table>
    </div>


    <h4 class="font-bold mb-4 uppercase mt-8">Rincian Jawaban:</h4>

    <table class="w-full table-bordered mb-8 text-sm">
        <thead>
            <tr>
                <th class="w-10 text-center">No</th>
                <th>Soal & Jawaban</th>

            </tr>
        </thead>
        <tbody>
            @php
                $orderedAnswers = collect($session->answers);
                if ($session->question_order && is_array($session->question_order)) {
                    $answersMap = $orderedAnswers->keyBy('question_id');
                    $orderedAnswers = collect();
                    foreach ($session->question_order as $qId) {
                        if (isset($answersMap[$qId])) {
                            $orderedAnswers->push($answersMap[$qId]);
                        }
                    }
                }
            @endphp
            @foreach($orderedAnswers as $index => $answer)
            <tr class="break-inside-avoid">
                <td class="text-center align-top">{{ $index + 1 }}</td>
                <td class="align-top">
                    <div class="mb-2 text-justify">
                        {!! $answer->question->text ?? 'Soal tidak ditemukan' !!}
                    </div>
                    
                    @if($answer->question && $answer->question->options && $answer->question->options->count() > 0)
                        @php
                            $orderedOptions = $answer->question->options;
                            if ($answer->options_order && is_array($answer->options_order)) {
                                $optionsMap = $answer->question->options->keyBy('id');
                                $ordered = collect();
                                foreach ($answer->options_order as $optId) {
                                    if (isset($optionsMap[$optId])) {
                                        $ordered->push($optionsMap[$optId]);
                                    }
                                }
                                if ($ordered->count() == $answer->question->options->count()) {
                                    $orderedOptions = $ordered;
                                }
                            }
                            $labels = ['A', 'B', 'C', 'D', 'E'];
                        @endphp
                        <div class="ml-4 mt-2 mb-4">
                            @foreach($orderedOptions->values() as $idx => $opt)
                                @php
                                    $isParticipantChoice = ($answer->option_id == $opt->id);
                                    $isCorrectOption = $opt->is_correct;
                                    
                                    $textClass = "text-black";
                                    $icon = "";
                                    if ($isParticipantChoice && $isCorrectOption) {
                                        $textClass = "text-green-700 font-bold";
                                        $icon = "✅ (Benar & Dipilih)";
                                    } elseif ($isParticipantChoice && !$isCorrectOption) {
                                        $textClass = "text-red-700 font-bold";
                                        $icon = "❌ (Salah, Dipilih)";
                                    } elseif (!$isParticipantChoice && $isCorrectOption) {
                                        $textClass = "text-green-700 font-bold";
                                        $icon = "👈 (Kunci Jawaban)";
                                    }
                                @endphp
                                <div class="flex items-start mb-1 {{ $textClass }}">
                                    <div class="w-6 font-bold">{{ $labels[$idx] ?? '-' }}.</div>
                                    <div class="flex-1">
                                        <span class="{{ ($isParticipantChoice && !$isCorrectOption) ? 'line-through' : '' }}">{!! $opt->text !!}</span>
                                        <span class="text-xs ml-2 italic font-normal">{{ $icon }}</span>
                                    </div>
                                </div>
                            @endforeach
                            @if(!$answer->option_id)
                                <div class="mt-2 text-sm italic text-red-600">- Peserta Tidak Menjawab Soal Ini -</div>
                            @endif
                        </div>
                    @else
                        {{-- Fallback untuk soal Essay / tanpa opsi --}}
                        <div class="mt-2 text-sm">
                            <span class="font-bold">Jawaban Peserta:</span> 
                            <span class="{{ $answer->is_correct ? 'text-green-700 font-bold' : 'text-red-700 font-bold' }}">
                                {{ $answer->answer_text ?? '- Tidak Menjawab -' }}
                            </span>
                        </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Print Button (Hidden on Print) -->
    <div class="fixed bottom-8 right-8 no-print flex space-x-4">
        <button onclick="window.close()" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow-lg font-bold">
            Tutup
        </button>
        <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-lg font-bold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak (Print)
        </button>
    </div>

</body>
</html>
