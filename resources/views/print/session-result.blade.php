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
                margin: 2cm 2cm; 
                size: A4;
            }
            body { 
                background: #fff; 
                margin: 0; 
                padding: 0; 
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
        <h3 class="text-lg font-bold uppercase underline">DETAIL HASIL UJIAN PESERTA</h3>
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
                <td class="align-top uppercase">{{ $session->exam->title }}</td>
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
                <td class="w-40 font-bold align-top text-red-600">Pelanggaran</td>
                <td class="w-4 text-center align-top text-red-600">:</td>
                <td class="align-top text-red-600 font-bold">{{ $session->violation_count }} kali</td>
            </tr>
            <tr>
                <td class="w-40 font-bold align-top text-lg pt-4">Skor Akhir</td>
                <td class="w-4 text-center align-top text-lg pt-4">:</td>
                <td class="align-top text-lg font-bold pt-4 {{ $session->score >= $session->exam->passing_grade ? 'text-green-600' : 'text-red-600' }}">
                    {{ round($session->score) }}
                </td>
            </tr>
        </table>
    </div>

    @if($violationLogs->count() > 0)
    <div class="mb-8 p-4 border border-red-500 bg-red-50" style="-webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: #fef2f2;">
        <h4 class="font-bold text-red-700 mb-2 uppercase">Log Pelanggaran Peserta:</h4>
        <ul class="list-disc list-inside text-sm text-red-600">
            @foreach($violationLogs as $log)
                <li>
                    <strong>{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}:</strong> 
                    @if($log->details && isset($log->details['type']))
                        @if($log->details['type'] == 'browser_blur')
                            Meninggalkan tab ujian (Browser tidak fokus)
                        @elseif($log->details['type'] == 'fullscreen_exit')
                            Keluar dari mode layar penuh (Fullscreen)
                        @elseif($log->details['type'] == 'window_resize')
                            Mengubah ukuran jendela browser
                        @else
                            {{ $log->details['type'] }}
                        @endif
                    @else
                        Terdeteksi aktivitas mencurigakan
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <h4 class="font-bold mb-4 uppercase mt-8">Rincian Jawaban:</h4>

    <table class="w-full table-bordered mb-8 text-sm">
        <thead>
            <tr>
                <th class="w-10 text-center">No</th>
                <th>Soal & Jawaban</th>
                <th class="w-24 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($session->answers as $index => $answer)
            <tr class="break-inside-avoid">
                <td class="text-center align-top">{{ $index + 1 }}</td>
                <td class="align-top">
                    <div class="mb-2 text-justify">
                        {!! $answer->question->text ?? 'Soal tidak ditemukan' !!}
                    </div>
                    
                    <div class="mt-2">
                        <span class="font-bold">Jawaban Peserta:</span> 
                        @if($answer->option)
                            <span class="{{ $answer->is_correct ? 'text-green-700 font-bold' : 'text-red-700 font-bold' }}">
                                {{ $answer->option->text }}
                            </span>
                        @elseif($answer->answer_text)
                            <span class="{{ $answer->is_correct ? 'text-green-700 font-bold' : 'text-red-700 font-bold' }}">
                                {{ $answer->answer_text }}
                            </span>
                        @else
                            <span class="italic text-gray-500">- Tidak Menjawab -</span>
                        @endif
                    </div>
                </td>
                <td class="text-center align-middle font-bold">
                    @if($answer->is_correct)
                        <span class="text-green-600">BENAR</span>
                    @elseif($answer->is_correct === 0)
                        <span class="text-red-600">SALAH</span>
                    @else
                        <span class="text-gray-500">KOSONG</span>
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
