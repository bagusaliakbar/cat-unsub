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

    @php
        $isPengawas = auth()->user() && auth()->user()->role === 'pengawas';
        $printRoute = $isPengawas ? 'pengawas.exams.monitor.print' : 'admin.exams.monitor.print';
        $backRoute = $isPengawas ? 'pengawas.exams.monitor' : 'admin.exams.monitor';
    @endphp

    <!-- Floating Toolbar (Hidden on Print) -->
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 no-print bg-white/95 backdrop-blur-md shadow-xl border border-gray-200 px-5 py-2.5 rounded-2xl flex items-center space-x-3 z-50">
        <a href="{{ route($backRoute, $exam->id) }}" class="text-xs font-semibold text-gray-700 hover:text-blue-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <span class="text-gray-300">|</span>
        <div class="flex items-center space-x-2">
            <label class="text-xs font-bold text-gray-700">Filter Desa:</label>
            <select onchange="window.location.href = this.value" class="text-xs font-medium border-gray-300 rounded-lg py-1 px-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                <option value="{{ route($printRoute, ['examId' => $exam->id, 'institution' => 'all', 'sort' => $sort ?? 'score']) }}" {{ ($institution ?? 'all') === 'all' ? 'selected' : '' }}>
                    Semua Desa (Gabungan)
                </option>
                @if(isset($institutions) && count($institutions) > 1)
                    <option value="{{ route($printRoute, ['examId' => $exam->id, 'institution' => 'all_separated', 'sort' => $sort ?? 'score']) }}" {{ ($institution ?? '') === 'all_separated' ? 'selected' : '' }}>
                        Cetak Semua (Pisah Lembar Per Desa)
                    </option>
                @endif
                @if(isset($institutions))
                    @foreach($institutions as $inst)
                        <option value="{{ route($printRoute, ['examId' => $exam->id, 'institution' => $inst, 'sort' => $sort ?? 'score']) }}" {{ ($institution ?? '') === $inst ? 'selected' : '' }}>
                            Desa: {{ $inst }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="flex items-center space-x-2">
            <label class="text-xs font-bold text-gray-700">Urutan:</label>
            <select onchange="window.location.href = this.value" class="text-xs font-medium border-gray-300 rounded-lg py-1 px-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                <option value="{{ route($printRoute, ['examId' => $exam->id, 'institution' => $institution ?? 'all', 'sort' => 'score']) }}" {{ ($sort ?? 'score') === 'score' ? 'selected' : '' }}>
                    Peringkat Nilai (Ranking)
                </option>
                <option value="{{ route($printRoute, ['examId' => $exam->id, 'institution' => $institution ?? 'all', 'sort' => 'participant_number']) }}" {{ ($sort ?? '') === 'participant_number' ? 'selected' : '' }}>
                    Nomor Peserta (Standar)
                </option>
                <option value="{{ route($printRoute, ['examId' => $exam->id, 'institution' => $institution ?? 'all', 'sort' => 'desa']) }}" {{ ($sort ?? '') === 'desa' ? 'selected' : '' }}>
                    Desa & No. Peserta
                </option>
                <option value="{{ route($printRoute, ['examId' => $exam->id, 'institution' => $institution ?? 'all', 'sort' => 'no_meja']) }}" {{ ($sort ?? '') === 'no_meja' ? 'selected' : '' }}>
                    Nomor Meja
                </option>
            </select>
        </div>

        <button onclick="window.print()" class="px-4 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-bold text-xs flex items-center shadow-sm">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print
        </button>
    </div>

    @if(isset($resultsData) && count($resultsData) > 0)
        {{-- MODE BATCH: CETAK SEMUA TERPISAH PER DESA --}}
        @foreach($resultsData as $resItem)
        <div class="results-sheet {{ !$loop->last ? 'page-break' : '' }}">
            <!-- Header / Kop Surat -->
            <div class="mb-4 text-center">
                <img src="{{ asset('images/kop-unsub.jpg') }}" alt="Kop Universitas Subang" class="w-full mx-auto h-auto object-contain">
            </div>

            @php
                $displayVillage = trim(preg_replace('/^desa\s+/i', '', $resItem['village']));
                $effectiveDistrict = !empty($resItem['district']) ? $resItem['district'] : (!empty($report->district) ? $report->district : null);
                $displayDistrict = !empty($effectiveDistrict) ? trim(preg_replace('/^kec(\.|\s+)/i', '', $effectiveDistrict)) : null;
            @endphp

            <!-- Title -->
            <div class="text-center mb-8">
                <h3 class="text-lg font-bold uppercase">DAFTAR HASIL UJIAN SELEKSI TERTULIS BERBASIS CAT</h3>
                <h3 class="text-lg font-bold uppercase">BAKAL CALON KEPALA DESA {{ strtoupper($displayVillage) }}</h3>
                @if(!empty($displayDistrict))
                    <h3 class="text-lg font-bold uppercase">KECAMATAN {{ strtoupper($displayDistrict) }} KABUPATEN SUBANG</h3>
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
                    <td class="w-40 font-bold align-top">Sesi</td>
                    <td class="w-4 text-center align-top">:</td>
                    <td class="align-top">{{ $exam->wave ? $exam->wave->name : ($resItem['sessions']->first()?->user?->wave?->name ?? '-') }}</td>
                </tr>
                <tr>
                    <td class="w-40 font-bold align-top">Tempat</td>
                    <td class="w-4 text-center align-top">:</td>
                    <td class="align-top">{{ $exam->location ?: 'Laboratorium Komputer Universitas Subang' }}</td>
                </tr>
            </table>

            <table class="w-full table-bordered text-center mb-8">
                <thead>
                    <tr>
                        <th class="w-10">No</th>
                        <th class="w-28">No. Peserta</th>
                        <th>Nama Peserta</th>
                        <th class="w-28">Desa</th>
                        <th class="w-28">Kecamatan</th>
                        <th class="w-24">Waktu Mulai</th>
                        <th class="w-24">Waktu Selesai</th>
                        <th class="w-20">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resItem['sessions'] as $index => $session)
                    <tr>
                        <td class="align-middle">{{ $index + 1 }}</td>
                        <td class="align-middle font-mono">{{ $session->user->participant_number ?? $session->user->nik }}</td>
                        <td class="text-left px-2 align-middle">{{ $session->user->name }}</td>
                        <td class="align-middle px-2">{{ $session->user->desa ?: ($session->user->institution ?: '-') }}</td>
                        <td class="align-middle px-2">{{ $session->user->kecamatan ?: '-' }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($session->started_at)->format('H:i:s') }}</td>
                        <td class="align-middle">
                            {{ $session->completed_at ? \Carbon\Carbon::parse($session->completed_at)->format('H:i:s') : '-' }}
                        </td>
                        <td class="align-middle font-bold">{{ round($session->score) }}</td>
                    </tr>
                    @endforeach
                    @if(count($resItem['sessions']) == 0)
                    <tr>
                        <td colspan="8" class="py-4 text-center text-gray-500">Belum ada peserta yang mengikuti ujian dari desa ini.</td>
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
                        <p class="font-bold"><u>{{ $report->committee_name ?? 'Kasda, S.T., M.T.' }}</u></p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    @else
        {{-- MODE SINGLE: GABUNGAN ATAU 1 DESA SPESIFIK --}}
        <!-- Header / Kop Surat -->
        <div class="mb-4 text-center">
            <img src="{{ asset('images/kop-unsub.jpg') }}" alt="Kop Universitas Subang" class="w-full mx-auto h-auto object-contain">
        </div>

        @php
            $isPerDesa = !empty($targetVillage) && $targetVillage !== 'all' && !str_starts_with(strtolower($targetVillage), 'gabungan');
            $displayVillage = $isPerDesa ? trim(preg_replace('/^desa\s+/i', '', $targetVillage)) : '';
            $effectiveDistrict = !empty($targetDistrict) ? $targetDistrict : (!empty($report->district) ? $report->district : null);
            $displayDistrict = !empty($effectiveDistrict) ? trim(preg_replace('/^kec(\.|\s+)/i', '', $effectiveDistrict)) : null;
        @endphp

        <!-- Title -->
        <div class="text-center mb-8">
            <h3 class="text-lg font-bold uppercase">DAFTAR HASIL UJIAN SELEKSI TERTULIS BERBASIS CAT</h3>
            @if($isPerDesa)
                <h3 class="text-lg font-bold uppercase">BAKAL CALON KEPALA DESA {{ strtoupper($displayVillage) }}</h3>
                @if(!empty($displayDistrict))
                    <h3 class="text-lg font-bold uppercase">KECAMATAN {{ strtoupper($displayDistrict) }} KABUPATEN SUBANG</h3>
                @else
                    <h3 class="text-lg font-bold uppercase">KABUPATEN SUBANG</h3>
                @endif
            @else
                {{-- Cetak Gabungan: Teks Kabupaten Subang dan Sesi ditiadakan dari judul --}}
                <h3 class="text-lg font-bold uppercase">SELEKSI BAKAL CALON KEPALA DESA</h3>
                @if(!empty($report->district))
                    <h3 class="text-lg font-bold uppercase">KECAMATAN {{ strtoupper($report->district) }}</h3>
                @endif
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
                <td class="w-40 font-bold align-top">Sesi</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top">{{ $exam->wave ? $exam->wave->name : ($sessions->first()?->user?->wave?->name ?? '-') }}</td>
            </tr>
            <tr>
                <td class="w-40 font-bold align-top">Tempat</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top">{{ $exam->location ?: 'Laboratorium Komputer Universitas Subang' }}</td>
            </tr>
        </table>

        <table class="w-full table-bordered text-center mb-8">
            <thead>
                <tr>
                    <th class="w-10">No</th>
                    <th class="w-28">No. Peserta</th>
                    <th>Nama Peserta</th>
                    <th class="w-28">Desa</th>
                    <th class="w-28">Kecamatan</th>
                    <th class="w-24">Waktu Mulai</th>
                    <th class="w-24">Waktu Selesai</th>
                    <th class="w-20">Skor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessions as $index => $session)
                <tr>
                    <td class="align-middle">{{ $index + 1 }}</td>
                    <td class="align-middle font-mono">{{ $session->user->participant_number ?? $session->user->nik }}</td>
                    <td class="text-left px-2 align-middle">{{ $session->user->name }}</td>
                    <td class="align-middle px-2">{{ $session->user->desa ?: ($session->user->institution ?: '-') }}</td>
                    <td class="align-middle px-2">{{ $session->user->kecamatan ?: '-' }}</td>
                    <td class="align-middle">{{ \Carbon\Carbon::parse($session->started_at)->format('H:i:s') }}</td>
                    <td class="align-middle">
                        {{ $session->completed_at ? \Carbon\Carbon::parse($session->completed_at)->format('H:i:s') : '-' }}
                    </td>
                    <td class="align-middle font-bold">{{ round($session->score) }}</td>
                </tr>
                @endforeach
                @if($sessions->count() == 0)
                <tr>
                    <td colspan="8" class="py-4 text-center text-gray-500">Belum ada peserta yang mengikuti ujian ini.</td>
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
                    <p class="font-bold"><u>{{ $report->committee_name ?? 'Kasda, S.T., M.T.' }}</u></p>
                </div>
            </div>
        </div>
    @endif

    <!-- Print Button (Hidden on Print) -->
    <div class="fixed bottom-8 right-8 no-print flex space-x-4">
        <a href="{{ route($backRoute, $exam->id) }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow-lg font-bold">
            Tutup
        </a>
        <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-lg font-bold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak (Print)
        </button>
    </div>

</body>
</html>
