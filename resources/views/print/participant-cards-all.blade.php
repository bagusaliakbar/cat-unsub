<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua Kartu Peserta Ujian</title>
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
                page-break-after: always;
            }
            .card-container:last-child {
                page-break-after: auto;
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
            page-break-after: always;
        }
        .card-container:last-child {
            page-break-after: auto;
        }
        /* Custom curve for header */
        .header-curve {
            position: relative;
            background-color: #263c7b;
            border-bottom-left-radius: 40% 10px;
            border-bottom-right-radius: 40% 10px;
            padding: 1rem 0;
            z-index: 10;
            margin: 0 10px;
        }
    </style>
</head>
<body class="p-4 sm:p-8" onload="window.print()">

    @foreach($participants as $participant)
    @php
        $exam = $participant->assignedExams()->first();
    @endphp
    <div class="card-container flex flex-col pt-4">
        <!-- Logo Section -->
        <div class="flex items-center justify-center bg-white z-20 pb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-auto object-contain mr-4">
            <h1 class="font-extrabold text-[26px] text-[#222a55] tracking-wide" style="font-family: 'Arial', sans-serif;">UNIVERSITAS SUBANG</h1>
        </div>

        <!-- Header Section -->
        <div class="header-curve shadow-md">
            <h2 class="text-center text-white font-extrabold text-2xl tracking-widest leading-tight">KARTU PESERTA</h2>
            <h3 class="text-center text-blue-100 font-bold text-sm tracking-widest mt-1">
                {{ $exam ? mb_strtoupper($exam->title) : 'UJIAN CAT' }}
            </h3>
        </div>

        <!-- Main Content (Photo + Details) -->
        <div class="flex-1 flex flex-col items-center px-10 pt-8 pb-4 relative z-0" style="margin-top: -15px;">
            <!-- Background Watermark -->
            <div class="absolute inset-0 z-0 opacity-5 flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Watermark" class="w-64 h-64 object-contain">
            </div>

            <div class="relative z-10 w-full flex flex-col items-center">
                <!-- Photo frame -->
                <div class="mb-6 relative">
                    <div class="w-32 h-40 border-4 border-white shadow-lg overflow-hidden bg-gray-100 rounded-md">
                        @if($participant->photo_path)
                            <img src="{{ Storage::url($participant->photo_path) }}" alt="Foto {{ $participant->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50 border-2 border-dashed border-gray-200">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Participant Name & Nomor -->
                <div class="text-center mb-8 w-full border-b-2 border-gray-100 pb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-1 leading-tight uppercase">{{ $participant->name }}</h3>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest">{{ $participant->participant_number }}</p>
                </div>

                <!-- Details Grid -->
                <div class="w-full space-y-4">
                    <div class="flex items-center">
                        <div class="w-8 flex justify-center text-[#263c7b]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        </div>
                        <div class="flex-1 ml-3">
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Nomor Ujian / ID</p>
                            <p class="font-bold text-gray-800 text-sm">{{ $participant->participant_number }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-8 flex justify-center text-[#263c7b]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div class="flex-1 ml-3">
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Password Ujian</p>
                            <p class="font-bold text-gray-800 text-sm tracking-widest font-mono">
                                {{ $participant->nik ? mb_substr($participant->nik, 0, 6) : '123456' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-8 flex justify-center text-[#263c7b]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex-1 ml-3">
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Tanggal & Gelombang</p>
                            <p class="font-bold text-gray-800 text-sm">
                                @if($participant->wave)
                                    {{ \Carbon\Carbon::parse($participant->wave->start_time)->translatedFormat('d F Y') }} <span class="mx-1 text-gray-300">|</span> {{ $participant->wave->name }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($exam)
                    <div class="flex items-center">
                        <div class="w-8 flex justify-center text-[#263c7b]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div class="flex-1 ml-3">
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Kategori Ujian</p>
                            <p class="font-bold text-gray-800 text-sm line-clamp-1">{{ $exam->title }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Warning Section -->
        <div class="mt-auto p-5 bg-yellow-50 border-t border-yellow-200">
            <div class="flex items-start">
                <svg class="w-4 h-4 text-yellow-600 mt-0.5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs text-yellow-800 font-medium leading-relaxed">
                    Bawa kartu ini saat ujian. Simpan ID dan Password dengan baik, jangan berikan kepada siapapun.
                </p>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Print Button (Visible only on screen) -->
    <div class="no-print fixed bottom-6 right-6 flex flex-col space-y-3 z-50">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg transition-transform transform hover:scale-105 flex items-center justify-center" title="Cetak Kartu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        </button>
        <button onclick="window.close()" class="bg-gray-600 hover:bg-gray-700 text-white p-4 rounded-full shadow-lg transition-transform transform hover:scale-105 flex items-center justify-center" title="Tutup">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</body>
</html>
