<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - {{ $exam->title }}</title>
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
                size: A4 portrait;
            }
            body { 
                background: #fff; 
                margin: 0; 
                padding: 1cm 2cm 2cm 2cm; 
            }
            .no-print { display: none !important; }
            .break-inside-avoid { break-inside: avoid; }
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
        <h3 class="text-lg font-bold uppercase">DAFTAR HASIL UJIAN SELEKSI TERTULIS BERBASIS CAT</h3>
        @if($report && $report->village)
        <h3 class="text-lg font-bold uppercase">CALON KEPALA DESA ANTAR WAKTU (PAW) DESA {{ strtoupper($report->village) }}</h3>
        <h3 class="text-lg font-bold uppercase">KECAMATAN {{ strtoupper($report->district) }} KABUPATEN SUBANG</h3>
        @endif
    </div>

    <!-- Info Ujian -->
    <table class="w-full mb-6 text-left align-top">
        <tr>
            <td class="w-40 font-bold align-top">Ujian</td>
            <td class="w-4 text-center align-top">:</td>
            <td class="align-top">{{ ucwords(strtolower($exam->title)) }}</td>
        </tr>
        <tr>
            <td class="w-40 font-bold align-top">Hari / Tanggal</td>
            <td class="w-4 text-center align-top">:</td>
            <td class="align-top">
                @if($exam->start_time)
                    {{ \Carbon\Carbon::parse($exam->start_time)->locale('id')->translatedFormat('l, d F Y') }}
                @else
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                @endif
            </td>
        </tr>
    </table>

    <table class="w-full table-bordered text-center mb-8">
        <thead>
            <tr>
                <th class="w-10">No</th>
                <th class="w-28">No. Peserta</th>
                <th>Nama Peserta</th>
                <th class="w-24">Waktu Mulai</th>
                <th class="w-24">Waktu Selesai</th>
                <th class="w-24">Skor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessions as $index => $session)
            <tr>
                <td class="align-middle">{{ $index + 1 }}</td>
                <td class="align-middle">{{ $session->user->participant_number ?? $session->user->nik }}</td>
                <td class="text-left px-2 align-middle">{{ $session->user->name }}</td>
                <td class="align-middle">{{ \Carbon\Carbon::parse($session->started_at)->format('H:i:s') }}</td>
                <td class="align-middle">
                    {{ $session->completed_at ? \Carbon\Carbon::parse($session->completed_at)->format('H:i:s') : '-' }}
                </td>
                <td class="align-middle font-bold">{{ round($session->score) }}</td>
            </tr>
            @endforeach
            @if($sessions->count() == 0)
            <tr>
                <td colspan="8" class="py-4 text-center">Belum ada peserta yang mengikuti ujian ini.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Signatures -->
    <div class="break-inside-avoid">
        <div class="flex justify-between mt-12">
            <div class="text-center w-1/2">
                <p>Mengetahui</p>
                <p>Penanggungjawab</p>
                <div class="h-24"></div>
                <p class="font-bold"><u>{{ $report->supervisor_name ?? 'Dr. Drs. H. Komir Bastaman, S.H., M.Si.' }}</u></p>
            </div>
            <div class="text-center w-1/2">
                <p>Subang, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                <p>Panitia Seleksi</p>
                <div class="h-24"></div>
                <p class="font-bold"><u>{{ $report->committee_name ?? 'Dr. Ujang Charda S., S.H., M.H., M.I.P., M.A.P.' }}</u></p>
            </div>
        </div>
    </div>

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
