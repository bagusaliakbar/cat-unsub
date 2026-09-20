<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir - {{ $exam->title }}</title>
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
            padding: 8px;
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
<body class="p-4 sm:p-8 max-w-4xl mx-auto text-black text-[11pt]" onload="initPrint()">

    <!-- Header / Kop Surat -->
    <div class="mb-4 text-center">
        <img src="{{ asset('images/kop-unsub.jpg') }}" alt="Kop Universitas Subang" class="w-full mx-auto h-auto object-contain">
    </div>

    <!-- Title -->
    <div class="text-center mb-8">
        <h3 class="text-lg font-bold uppercase">DAFTAR HADIR PESERTA UJIAN SELEKSI TERTULIS BERBASIS CAT</h3>
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
        <tr>
            <td class="w-40 font-bold align-top">Waktu / Durasi</td>
            <td class="w-4 text-center align-top">:</td>
            <td class="align-top">
                @if($exam->start_time)
                    {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} WIB s.d {{ $exam->end_time ? \Carbon\Carbon::parse($exam->end_time)->format('H:i') . ' WIB' : 'Selesai' }} ({{ $exam->duration_minutes }} Menit)
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    <table class="w-full table-bordered mb-8">
        <thead>
            <tr>
                <th class="w-12 text-center">No</th>
                <th class="w-32 text-center">Nomor Peserta</th>
                <th class="text-center">Nama Peserta</th>
                <th class="w-48 text-center">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exam->participants as $index => $participant)
            <tr>
                <td class="text-center align-middle">{{ $index + 1 }}</td>
                <td class="text-center align-middle">{{ $participant->participant_number ?? $participant->nik }}</td>
                <td class="px-3 align-middle">{{ $participant->name }}</td>
                <td class="align-middle px-3">
                    <div class="h-8 relative">
                        @if(($index + 1) % 2 != 0)
                            <span class="absolute left-0 top-1 text-sm">{{ $index + 1 }}. ............</span>
                        @else
                            <span class="absolute right-4 top-1 text-sm">{{ $index + 1 }}. ............</span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
            @if($exam->participants->count() == 0)
            <tr>
                <td colspan="4" class="text-center py-4 text-gray-500">Belum ada peserta yang ditugaskan ke ujian ini.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Signatures -->
    <div class="break-inside-avoid">
        <div class="flex justify-between mt-12">
            <div class="text-center w-1/2">
                <p>Mengetahui</p>
                <p>Panitia Seleksi</p>
                <div class="h-24"></div>
                <p class="font-bold"><u>{{ $report->committee_name ?? 'Dr. Ujang Charda S., S.H., M.H., M.I.P., M.A.P.' }}</u></p>
            </div>
            <div class="text-center w-1/2">
                <p>Subang, {{ $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->locale('id')->translatedFormat('d F Y') : \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                <p>Pengawas Ruangan</p>
                <div class="h-24"></div>
                <p class="font-bold"><u><span id="nama-pengawas">..................................................</span></u></p>
            </div>
        </div>
    </div>

    <!-- Print Button (Hidden on Print) -->
    <div class="fixed bottom-8 right-8 no-print flex space-x-4">
        <a href="{{ route('admin.exams') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow-lg font-bold">
            Tutup
        </a>
        <button onclick="initPrint()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-lg font-bold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak (Print)
        </button>
    </div>

    <script>
        function initPrint() {
            let currentName = document.getElementById('nama-pengawas').innerText;
            let defaultName = currentName.includes('.....') ? '' : currentName;
            
            let nama = prompt("Masukkan Nama Pengawas Ruangan (Kosongkan jika ingin berupa titik-titik):", defaultName);
            
            if (nama !== null) {
                if (nama.trim() !== "") {
                    document.getElementById('nama-pengawas').innerText = nama;
                } else {
                    document.getElementById('nama-pengawas').innerText = "..................................................";
                }
            }
            
            // Beri jeda sedikit agar DOM update sebelum jendela print terbuka
            setTimeout(() => {
                window.print();
            }, 100);
        }
    </script>
</body>
</html>
