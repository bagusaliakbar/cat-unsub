<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Peserta Ujian - {{ $participant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        
        body { 
            font-family: 'Inter', sans-serif; 
            background: #f3f4f6; 
            color: #1e293b;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        @media print {
            @page { 
                margin: 0; 
                size: A4 portrait;
            }
            body { 
                background: #fff; 
                margin: 0; 
                padding: 1cm; 
            }
            .no-print { display: none !important; }
            .card-container {
                box-shadow: none !important;
                border: none !important;
                page-break-inside: avoid;
            }
        }
        .card-container {
            width: 14.8cm; /* A5 width approx */
            min-height: 21cm;
            background: #fff;
            margin: 20px auto;
            position: relative;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        /* Custom curve for header */
        .header-curve {
            position: relative;
            background-color: #263c7b; /* Closer to the dark blue in image */
            border-bottom-left-radius: 40% 10px;
            border-bottom-right-radius: 40% 10px;
            padding: 1rem 0;
            z-index: 10;
            margin: 0 10px;
        }
    </style>
</head>
@php
    $exam = $participant->assignedExams()->first();
@endphp
<body class="p-4 sm:p-8" onload="window.print()">

    <div class="card-container flex flex-col pt-4">
        <!-- Logo Section -->
        <div class="flex items-center justify-center bg-white z-20 pb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-auto object-contain mr-4">
            <h1 class="font-extrabold text-[26px] text-[#222a55] tracking-wide" style="font-family: 'Arial', sans-serif;">UNIVERSITAS SUBANG</h1>
        </div>

        <!-- Header Section -->
        <div class="header-curve text-center">
            <h1 class="font-bold text-[22px] text-white tracking-widest">KARTU TANDA PESERTA UJIAN</h1>
            <h2 class="font-semibold text-xs text-blue-100 tracking-[0.2em] mt-1">SELEKSI TERTULIS BERBASIS CAT</h2>
        </div>

        <!-- Body Section -->
        <div class="px-8 py-6 flex-1 flex flex-col relative z-20 -mt-2">
            <!-- Info Peserta / Token Highlight -->
            <div class="bg-[#f0f4f8] rounded-[1rem] p-3 flex items-center justify-between mb-6 border border-gray-100">
                <span class="font-bold text-[#2b3a70] ml-4 text-base">Nomor Peserta / Token :</span>
                <span class="font-extrabold text-3xl text-[#1e2a5a] tracking-wider mr-6" style="font-family: 'Arial', sans-serif;">{{ $participant->participant_number ?? '-' }}</span>
            </div>

            <div class="flex justify-between gap-4">
                <!-- Data Table -->
                <div class="flex-1">
                    <table class="w-full text-sm">
                        <tbody>
                            <tr class="h-10">
                                <td class="w-32 text-gray-700">Nama Peserta</td>
                                <td class="w-4 text-center">:</td>
                                <td class="font-bold text-gray-900">{{ strtoupper($participant->name) }}</td>
                            </tr>
                            <tr class="h-10">
                                <td class="text-gray-700">NIK</td>
                                <td class="text-center">:</td>
                                <td class="font-medium text-gray-900">{{ $participant->nik ?? '-' }}</td>
                            </tr>
                            <tr class="h-10">
                                <td class="text-gray-700">Tanggal Ujian</td>
                                <td class="text-center">:</td>
                                <td class="font-medium text-gray-900">
                                    {{ $exam && $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->locale('id')->isoFormat('dddd, D MMMM Y') : '-' }}
                                </td>
                            </tr>
                            <tr class="h-10">
                                <td class="text-gray-700">Waktu Ujian</td>
                                <td class="text-center">:</td>
                                <td class="font-medium text-gray-900">
                                    {{ $exam && $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('H.i') . ' - ' . \Carbon\Carbon::parse($exam->end_time)->format('H.i') . ' WIB' : '-' }}
                                </td>
                            </tr>
                            <tr class="h-10">
                                <td class="text-gray-700">Lokasi Ujian</td>
                                <td class="text-center">:</td>
                                <td class="font-medium text-gray-900">Universitas Subang</td>
                            </tr>
                            <tr class="h-10">
                                <td class="text-gray-700">Ruang</td>
                                <td class="text-center">:</td>
                                <td class="font-medium text-gray-900">{{ $exam ? $exam->location : '-' }}</td>
                            </tr>
                            <tr class="h-10">
                                <td class="text-gray-700">Sesi</td>
                                <td class="text-center">:</td>
                                <td class="font-medium text-gray-900">{{ $participant->wave ? $participant->wave->name : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Right Side (Photo & QR) -->
                <div class="flex flex-col items-center w-36 shrink-0 pt-2">
                    <div class="w-28 h-36 border border-gray-300 rounded-sm overflow-hidden bg-gray-100 flex items-center justify-center mb-1">
                        @if($participant->profile_photo_path)
                            <img src="{{ asset('storage/' . $participant->profile_photo_path) }}" alt="Foto Peserta" class="w-full h-full object-cover">
                        @else
                            <span class="text-gray-400 text-xs">Foto 3 x 4</span>
                        @endif
                    </div>
                    
                    <div class="bg-white p-2">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate(route('verify', $participant->participant_number)) !!}
                    </div>
                    <p class="text-center font-bold text-sm text-gray-800 tracking-widest">{{ $participant->participant_number }}</p>
                </div>
            </div>
            
            <!-- Notes Section -->
            <div class="mt-8 bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-4 shadow-sm relative z-20">
                <div class="w-8 h-8 bg-blue-900 text-white rounded-full flex items-center justify-center font-extrabold text-lg shrink-0 shadow-md">
                    !
                </div>
                <div class="text-[11px] text-gray-700 leading-relaxed pt-0.5">
                    <p class="font-bold text-sm text-gray-900 mb-1">Catatan:</p>
                    <ol class="list-decimal pl-4 space-y-1">
                        <li>Kartu ini wajib dibawa dan ditunjukkan kepada panitia/pengawas pada saat pelaksanaan ujian.</li>
                        <li>Peserta wajib membawa KTP/Kartu Identitas asli yang masih berlaku.</li>
                        <li>Datang minimal 15 menit sebelum waktu ujian.</li>
                        <li>Patuhi tata tertib pelaksanaan ujian.</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <!-- Footer Waves Graphic -->
        <div class="mt-auto relative z-10 -mt-10">
            <svg viewBox="0 0 1440 220" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full block transform translate-y-1">
                <!-- Lightest blue (bottom) -->
                <path d="M0 64L48 80C96 96 192 128 288 122.7C384 117 480 75 576 69.3C672 64 768 96 864 128C960 160 1056 192 1152 192C1248 192 1344 160 1392 144L1440 128V220H1392C1344 220 1248 220 1152 220C1056 220 960 220 864 220C768 220 672 220 576 220C480 220 384 220 288 220C192 220 96 220 48 220H0V64Z" fill="#bae6fd"/>
                <!-- Medium blue -->
                <path d="M0 128L48 117.3C96 107 192 85 288 96C384 107 480 149 576 160C672 171 768 149 864 122.7C960 96 1056 64 1152 64C1248 64 1344 96 1392 112L1440 128V220H1392C1344 220 1248 220 1152 220C1056 220 960 220 864 220C768 220 672 220 576 220C480 220 384 220 288 220C192 220 96 220 48 220H0V128Z" fill="#38bdf8"/>
                <!-- Dark blue (top) -->
                <path d="M0 192L48 176C96 160 192 128 288 117.3C384 107 480 117 576 133.3C672 149 768 171 864 165.3C960 160 1056 128 1152 117.3C1248 107 1344 117 1392 122.7L1440 128V220H1392C1344 220 1248 220 1152 220C1056 220 960 220 864 220C768 220 672 220 576 220C480 220 384 220 288 220C192 220 96 220 48 220H0V192Z" fill="#0284c7"/>
            </svg>
        </div>
    </div>

    <!-- Print Button (Hidden on Print) -->
    <div class="fixed bottom-8 right-8 no-print flex space-x-4 z-50">
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
