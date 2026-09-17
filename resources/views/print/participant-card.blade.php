<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Peserta Ujian - {{ $participant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap');
        
        body { 
            font-family: 'Times New Roman', Times, serif; 
            background: #f3f4f6; 
            color: #000;
        }
        @media print {
            @page { 
                margin: 1cm; 
                size: A4;
            }
            body { 
                background: #fff; 
                margin: 0; 
                padding: 0; 
            }
            .no-print { display: none !important; }
            .card-container {
                box-shadow: none !important;
                border: 2px dashed #ccc !important;
                page-break-inside: avoid;
            }
        }
        .card-container {
            width: 10cm;
            height: auto;
            min-height: 14cm;
            border: 1px solid #ddd;
            background: #fff;
            margin: 20px auto;
            position: relative;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="p-4 sm:p-8" onload="window.print()">

    <div class="card-container p-4">
        <!-- Header / Kop -->
        <div class="flex items-center justify-center border-b-2 border-black pb-2 mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto object-contain mr-3">
            <div class="text-center">
                <h1 class="font-bold text-sm uppercase">KARTU TANDA PESERTA UJIAN</h1>
                <h2 class="font-bold text-xs uppercase">SELEKSI TERTULIS BERBASIS CAT</h2>
            </div>
        </div>

        <!-- Info Peserta -->
        <table class="w-full text-xs mb-4">
            <tr>
                <td class="w-24 font-bold py-1 align-top">Nomor Token</td>
                <td class="w-2 text-center py-1 align-top">:</td>
                <td class="py-1 align-top font-bold">{{ $participant->participant_number ?? '-' }}</td>
            </tr>
            <tr>
                <td class="w-24 font-bold py-1 align-top">NIK / Username</td>
                <td class="w-2 text-center py-1 align-top">:</td>
                <td class="py-1 align-top font-bold">{{ $participant->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td class="w-24 font-bold py-1 align-top">Nama Peserta</td>
                <td class="w-2 text-center py-1 align-top">:</td>
                <td class="py-1 align-top">{{ strtoupper($participant->name) }}</td>
            </tr>
            <tr>
                <td class="w-24 font-bold py-1 align-top">TTL</td>
                <td class="w-2 text-center py-1 align-top">:</td>
                <td class="py-1 align-top">
                    {{ $participant->birth_place ?? '-' }}, 
                    {{ $participant->birth_date ? \Carbon\Carbon::parse($participant->birth_date)->format('d-m-Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="w-24 font-bold py-1 align-top">Instansi / Asal</td>
                <td class="w-2 text-center py-1 align-top">:</td>
                <td class="py-1 align-top">{{ $participant->institution ?? '-' }}</td>
            </tr>
            <tr>
                <td class="w-24 font-bold py-1 align-top">Gelombang</td>
                <td class="w-2 text-center py-1 align-top">:</td>
                <td class="py-1 align-top">{{ $participant->wave ? $participant->wave->name : '-' }}</td>
            </tr>
        </table>

        <!-- Foto dan TTD -->
        <div class="flex justify-between items-end mt-6">
            <div class="w-20 h-24 border-2 border-gray-400 flex items-center justify-center text-gray-400 text-[10px]">
                Pas Foto 3x4
            </div>
            <div class="text-center text-xs">
                <p class="mb-12">Tanda Tangan Peserta,</p>
                <p>.......................................</p>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-dashed border-gray-400 text-[10px] text-justify text-gray-600">
            <strong>Catatan:</strong> Kartu ini wajib dibawa saat pelaksanaan ujian dan ditunjukkan kepada panitia/pengawas beserta kartu identitas asli (KTP/KK).
        </div>
    </div>

    <!-- Print Button (Hidden on Print) -->
    <div class="fixed bottom-8 right-8 no-print flex space-x-4">
        <a href="{{ route('admin.participants') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow-lg font-bold text-sm">
            Tutup
        </a>
        <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-lg font-bold flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Kartu
        </button>
    </div>

</body>
</html>
