<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Pelanggaran - {{ $session->user->name }}</title>
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
            background-color: #f1f5f9 !important;
            color: #000;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .editable {
            min-height: 20px;
            display: inline-block;
            outline: none;
            width: 100%;
        }
        .editable:empty:before {
            content: attr(data-placeholder);
            color: gray;
            font-style: italic;
        }
        @media print {
            .editable:empty:before {
                content: "";
            }
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
        <h3 class="text-lg font-bold uppercase">LAPORAN PELANGGARAN PESERTA UJIAN</h3>
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
                <td class="w-40 font-bold align-top">Total Pelanggaran</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top font-bold">{{ $session->violation_count }} kali</td>
            </tr>
        </table>
    </div>

    <h4 class="font-bold mb-2 uppercase">Rincian Log Pelanggaran:</h4>
    
    @if($violationLogs->count() > 0)
    <table class="w-full table-bordered mb-8 text-sm">
        <thead>
            <tr>
                <th class="w-10 text-center">No</th>
                <th class="w-32 text-center">Waktu</th>
                <th>Jenis Pelanggaran</th>
                <th>Tindakan / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($violationLogs as $index => $log)
            <tr class="break-inside-avoid">
                <td class="text-center align-top">{{ $index + 1 }}</td>
                <td class="text-center align-top">{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i:s') }}</td>
                <td class="align-top">
                    @if($log->details && isset($log->details['type']))
                        @if($log->details['type'] == 'browser_blur')
                            Meninggalkan tab ujian (Browser Blur)
                        @elseif($log->details['type'] == 'fullscreen_exit')
                            Keluar dari mode layar penuh (Exit Fullscreen)
                        @elseif($log->details['type'] == 'window_resize')
                            Mengubah ukuran jendela browser (Window Resize)
                        @else
                            {{ $log->details['type'] }}
                        @endif
                    @else
                        Aktivitas mencurigakan
                    @endif
                </td>
                <td class="align-top">
                    <div contenteditable="true" class="editable w-full h-full" data-placeholder="Ketik tindakan...">Peringatan Otomatis Sistem</div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="p-4 border border-gray-300 text-center italic mb-8">
        Tidak ditemukan rekaman pelanggaran (Peserta tertib).
    </div>
    @endif

    <div class="mt-4 mb-8">
        <h4 class="font-bold mb-2 uppercase">Keputusan / Sanksi:</h4>
        <div class="border border-black p-2 min-h-[100px]">
            <div contenteditable="true" class="editable w-full h-full" data-placeholder="Ketik keputusan terkait sanksi pelanggaran di sini..."></div>
        </div>
    </div>

    <!-- Tanda Tangan -->
    <div class="mt-8 break-inside-avoid">
        <table class="w-full text-center">
            <tr>
                <td class="w-1/2">
                    <br>
                    Peserta Ujian,
                    <br><br><br><br>
                    <span class="font-bold underline">{{ $session->user->name }}</span>
                </td>
                <td class="w-1/2">
                    Subang, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                    Pengawas Ruangan / Operator CAT,
                    <br><br><br><br>
                    <span class="font-bold underline" id="pengawasName">___________________________</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Print Button (Hidden on Print) -->
    <div class="fixed bottom-8 right-8 no-print flex space-x-4">
        <button onclick="window.close()" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow-lg font-bold">
            Tutup
        </button>
        <button onclick="window.print()" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-lg font-bold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak (Print)
        </button>
    </div>

    <script>
        function initPrint() {
            let pengawas = prompt("Masukkan nama Pengawas Ruangan / Operator CAT (Opsional):", "");
            if (pengawas) {
                document.getElementById('pengawasName').innerText = pengawas;
                document.getElementById('pengawasName').classList.remove('underline');
            }
            window.print();
        }
    </script>
</body>
</html>
