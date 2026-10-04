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
            .page-break { 
                page-break-after: always; 
                break-after: page;
            }
        }
        .page-break { 
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 2px dashed #cbd5e1;
        }
        @media print {
            .page-break {
                margin-bottom: 0;
                padding-bottom: 0;
                border-bottom: none;
            }
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

    <!-- Floating Toolbar (Hidden on Print) -->
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 no-print bg-white/95 backdrop-blur-md shadow-xl border border-gray-200 px-5 py-2.5 rounded-2xl flex items-center space-x-3 z-50">
        <a href="{{ route('admin.exams') }}" class="text-xs font-semibold text-gray-700 hover:text-blue-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <span class="text-gray-300">|</span>
        <div class="flex items-center space-x-2">
            <label class="text-xs font-bold text-gray-700">Filter Desa:</label>
            <select onchange="window.location.href = this.value" class="text-xs font-medium border-gray-300 rounded-lg py-1 px-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                <option value="{{ route('admin.exams.attendance', ['examId' => $exam->id, 'institution' => 'all']) }}" {{ ($institution ?? 'all') === 'all' ? 'selected' : '' }}>
                    Semua Desa (Gabungan)
                </option>
                @if(isset($institutions) && count($institutions) > 1)
                    <option value="{{ route('admin.exams.attendance', ['examId' => $exam->id, 'institution' => 'all_separated']) }}" {{ ($institution ?? '') === 'all_separated' ? 'selected' : '' }}>
                        Cetak Semua (Pisah Lembar Per Desa)
                    </option>
                @endif
                @if(isset($institutions))
                    @foreach($institutions as $inst)
                        <option value="{{ route('admin.exams.attendance', ['examId' => $exam->id, 'institution' => $inst]) }}" {{ ($institution ?? '') === $inst ? 'selected' : '' }}>
                            Desa: {{ $inst }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <button onclick="initPrint()" class="px-4 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-bold text-xs flex items-center shadow-sm">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print
        </button>
    </div>

    @if(isset($attendanceData) && count($attendanceData) > 0)
        {{-- MODE BATCH: CETAK SEMUA TERPISAH PER INSTANSI --}}
        @foreach($attendanceData as $attItem)
        <div class="attendance-sheet {{ !$loop->last ? 'page-break' : '' }}">
            <!-- Header / Kop Surat -->
            <div class="mb-4 text-center">
                <img src="{{ asset('images/kop-unsub.jpg') }}" alt="Kop Universitas Subang" class="w-full mx-auto h-auto object-contain">
            </div>

            <!-- Title -->
            <div class="text-center mb-8">
                <h3 class="text-lg font-bold uppercase">DAFTAR HADIR PESERTA UJIAN SELEKSI TERTULIS BERBASIS CAT</h3>
                <h3 class="text-lg font-bold uppercase">CALON KEPALA DESA ANTAR WAKTU (PAW) DESA {{ strtoupper($attItem['village']) }}</h3>
                @if(!empty($report->district))
                <h3 class="text-lg font-bold uppercase">KECAMATAN {{ strtoupper($report->district) }} KABUPATEN SUBANG</h3>
                @else
                <h3 class="text-lg font-bold uppercase">KABUPATEN SUBANG</h3>
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
                        <th class="w-10 text-center">No</th>
                        <th class="w-20 text-center">No. Meja</th>
                        <th class="w-28 text-center">Nomor Peserta</th>
                        <th class="text-center">Nama Peserta</th>
                        <th class="w-32 text-center">Desa</th>
                        <th class="w-32 text-center">Kecamatan</th>
                        <th class="w-44 text-center">Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attItem['participants'] as $index => $participant)
                    <tr>
                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                        <td class="text-center align-middle font-bold font-mono">{{ $participant->no_meja ?? '-' }}</td>
                        <td class="text-center align-middle font-mono">{{ $participant->participant_number ?? $participant->nik }}</td>
                        <td class="px-3 align-middle">{{ $participant->name }}</td>
                        <td class="px-3 align-middle text-center">{{ $participant->desa ?: ($participant->institution ?: '-') }}</td>
                        <td class="px-3 align-middle text-center">{{ $participant->kecamatan ?: '-' }}</td>
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
                    @if(count($attItem['participants']) == 0)
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">Belum ada peserta dari desa {{ $attItem['village'] }}.</td>
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
                        <p class="font-bold"><u>{{ $report->committee_name ?? 'Kasda, S.T., M.T.' }}</u></p>
                    </div>
                    <div class="text-center w-1/2">
                        <p>Subang, {{ $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->locale('id')->translatedFormat('d F Y') : \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                        <p>Pengawas Ruangan</p>
                        <div class="h-24"></div>
                        <p class="font-bold"><u><span class="nama-pengawas">..................................................</span></u></p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    @else
        {{-- MODE SINGLE: GABUNGAN ATAU 1 INSTANSI SPESIFIK --}}
        <!-- Header / Kop Surat -->
        <div class="mb-4 text-center">
            <img src="{{ asset('images/kop-unsub.jpg') }}" alt="Kop Universitas Subang" class="w-full mx-auto h-auto object-contain">
        </div>

        <!-- Title -->
        <div class="text-center mb-8">
            <h3 class="text-lg font-bold uppercase">DAFTAR HADIR PESERTA UJIAN SELEKSI TERTULIS BERBASIS CAT</h3>
            @if(!empty($targetVillage) && $targetVillage !== 'all' && !str_starts_with(strtolower($targetVillage), 'gabungan'))
                <h3 class="text-lg font-bold uppercase">CALON KEPALA DESA ANTAR WAKTU (PAW) DESA {{ strtoupper($targetVillage) }}</h3>
            @else
                <h3 class="text-lg font-bold uppercase">CALON KEPALA DESA ANTAR WAKTU (PAW)</h3>
            @endif
            @if(!empty($report->district))
                <h3 class="text-lg font-bold uppercase">KECAMATAN {{ strtoupper($report->district) }} KABUPATEN SUBANG</h3>
            @else
                <h3 class="text-lg font-bold uppercase">KABUPATEN SUBANG</h3>
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
                    <th class="w-10 text-center">No</th>
                    <th class="w-20 text-center">No. Meja</th>
                    <th class="w-28 text-center">Nomor Peserta</th>
                    <th class="text-center">Nama Peserta</th>
                    <th class="w-32 text-center">Desa</th>
                    <th class="w-32 text-center">Kecamatan</th>
                    <th class="w-44 text-center">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $index => $participant)
                <tr>
                    <td class="text-center align-middle">{{ $index + 1 }}</td>
                    <td class="text-center align-middle font-bold font-mono">{{ $participant->no_meja ?? '-' }}</td>
                    <td class="text-center align-middle font-mono">{{ $participant->participant_number ?? $participant->nik }}</td>
                    <td class="px-3 align-middle">{{ $participant->name }}</td>
                    <td class="px-3 align-middle text-center">{{ $participant->desa ?: ($participant->institution ?: '-') }}</td>
                    <td class="px-3 align-middle text-center">{{ $participant->kecamatan ?: '-' }}</td>
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
                @if(count($participants) == 0)
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">Belum ada peserta yang sesuai dengan pilihan ini.</td>
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
                    <p class="font-bold"><u>{{ $report->committee_name ?? 'Kasda, S.T., M.T.' }}</u></p>
                </div>
                <div class="text-center w-1/2">
                    <p>Subang, {{ $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->locale('id')->translatedFormat('d F Y') : \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                    <p>Pengawas Ruangan</p>
                    <div class="h-24"></div>
                    <p class="font-bold"><u><span class="nama-pengawas">..................................................</span></u></p>
                </div>
            </div>
        </div>
    @endif

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
            let elements = document.querySelectorAll('.nama-pengawas');
            let currentName = elements.length > 0 ? elements[0].innerText : '';
            let defaultName = currentName.includes('.....') ? '' : currentName;
            
            let nama = prompt("Masukkan Nama Pengawas Ruangan (Kosongkan jika ingin berupa titik-titik):", defaultName);
            
            if (nama !== null) {
                let formattedName = nama.trim() !== "" ? nama : "..................................................";
                elements.forEach(el => el.innerText = formattedName);
            }
            
            // Beri jeda sedikit agar DOM update sebelum jendela print terbuka
            setTimeout(() => {
                window.print();
            }, 100);
        }
    </script>
</body>
</html>
