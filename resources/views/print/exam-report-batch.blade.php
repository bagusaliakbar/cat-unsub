<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Semua Instansi - {{ $exam->title }}</title>
    <!-- Tailwind CSS -->
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
            padding: 4px 8px;
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

    <!-- Floating Toolbar (Hidden on Print) -->
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 no-print bg-white/95 backdrop-blur-md shadow-xl border border-gray-200 px-5 py-2.5 rounded-2xl flex items-center space-x-3 z-50">
        <a href="{{ route('admin.exams.report', $exam->id) }}" class="text-xs font-semibold text-gray-700 hover:text-blue-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Form
        </a>
        <span class="text-gray-300">|</span>
        <div class="flex items-center space-x-2">
            <label class="text-xs font-bold text-gray-700">Filter Cetak:</label>
            <select onchange="window.location.href = this.value" class="text-xs font-medium border-gray-300 rounded-lg py-1 px-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                <option value="{{ route('admin.exams.report.print', ['examId' => $exam->id, 'institution' => 'all', 'scope' => $scope ?? 'single', 'sort' => $sort ?? 'participant_number']) }}">
                    Semua Instansi (Gabungan)
                </option>
                <option value="{{ route('admin.exams.report.print', ['examId' => $exam->id, 'institution' => 'all_separated', 'scope' => $scope ?? 'single', 'sort' => $sort ?? 'participant_number']) }}" selected>
                    Cetak Semua (Pisah Lembar Per Instansi)
                </option>
                @if(isset($institutions))
                    @foreach($institutions as $inst)
                        <option value="{{ route('admin.exams.report.print', ['examId' => $exam->id, 'institution' => $inst, 'scope' => $scope ?? 'single', 'sort' => $sort ?? 'participant_number']) }}">
                            Desa: {{ $inst }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="flex items-center space-x-2">
            <label class="text-xs font-bold text-gray-700">Urutan:</label>
            <select onchange="window.location.href = this.value" class="text-xs font-medium border-gray-300 rounded-lg py-1 px-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                <option value="{{ route('admin.exams.report.print', ['examId' => $exam->id, 'institution' => $institution ?? 'all_separated', 'scope' => $scope ?? 'single', 'sort' => 'participant_number']) }}" {{ ($sort ?? 'participant_number') === 'participant_number' ? 'selected' : '' }}>
                    Nomor Peserta (Standar)
                </option>
                <option value="{{ route('admin.exams.report.print', ['examId' => $exam->id, 'institution' => $institution ?? 'all_separated', 'scope' => $scope ?? 'single', 'sort' => 'desa']) }}" {{ ($sort ?? '') === 'desa' ? 'selected' : '' }}>
                    Desa & No. Peserta
                </option>
                <option value="{{ route('admin.exams.report.print', ['examId' => $exam->id, 'institution' => $institution ?? 'all_separated', 'scope' => $scope ?? 'single', 'sort' => 'score']) }}" {{ ($sort ?? '') === 'score' ? 'selected' : '' }}>
                    Peringkat Nilai (Ranking)
                </option>
                <option value="{{ route('admin.exams.report.print', ['examId' => $exam->id, 'institution' => $institution ?? 'all_separated', 'scope' => $scope ?? 'single', 'sort' => 'no_meja']) }}" {{ ($sort ?? '') === 'no_meja' ? 'selected' : '' }}>
                    Nomor Meja
                </option>
            </select>
        </div>

        @if(!empty($isCombinedSession))
            <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-lg border border-emerald-300 flex items-center gap-1 shadow-xs">
                👥 Pleno Sesi ({{ $combinedLocations }})
            </span>
        @elseif(!empty($exam->location))
            <span class="text-xs bg-gray-100 text-gray-700 font-medium px-2 py-1 rounded-lg border border-gray-200">
                Ruang: {{ $exam->location }}
            </span>
        @endif
        <button onclick="window.print()" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-xs flex items-center shadow-sm">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Semua
        </button>
    </div>

    @foreach($reportsData as $item)
    <div class="report-sheet {{ !$loop->last ? 'page-break' : '' }}">
        <!-- Header / Kop Surat -->
        <div class="mb-4 text-center">
            <img src="{{ asset('images/kop-unsub.jpg') }}" alt="Kop Universitas Subang" class="w-full mx-auto h-auto object-contain">
        </div>

        <!-- Title -->
        <div class="text-center mb-6">
            <h3 class="text-lg font-bold uppercase">BERITA ACARA HASIL SELEKSI TERTULIS BERBASIS CAT</h3>
            <h3 class="text-lg font-bold uppercase">BAKAL CALON KEPALA DESA {{ strtoupper($item['village']) }}</h3>
            @if(!empty($report->district))
            <h3 class="text-lg font-bold uppercase">KECAMATAN {{ strtoupper($report->district) }} KABUPATEN SUBANG</h3>
            @else
            <h3 class="text-lg font-bold uppercase">KABUPATEN SUBANG</h3>
            @endif
            <p class="mt-1 font-bold">Nomor: {{ $report->reference_number ?? '[Nomor Surat]' }}</p>
        </div>

        <!-- Content -->
        <div class="text-justify mb-4">
            <p class="indent-10">Pada hari ini, <strong>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l') }}</strong> tanggal <strong>{{ \Carbon\Carbon::now()->translatedFormat('d') }}</strong> bulan <strong>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F') }}</strong> tahun <strong>{{ ucwords(\NumberFormatter::create('id_ID', \NumberFormatter::SPELLOUT)->format(\Carbon\Carbon::now()->year)) }}</strong>, bertempat di Laboratorium Komputer Universitas Subang{{ !empty($isCombinedSession) && !empty($combinedLocations) ? ' (Ruang ' . $combinedLocations . ')' : (!empty($exam->location) ? ' (Ruang ' . $exam->location . ')' : '') }}, telah dilaksanakan Ujian Penyaringan Seleksi Tertulis berbasis Computer Assisted Test (CAT){{ $exam->wave ? ' (' . $exam->wave->name . ')' : '' }} bagi Bakal Calon Kepala Desa {{ ucwords(strtolower($item['village'])) }} @if(!empty($report->district)) Kecamatan {{ ucwords(strtolower($report->district)) }} @endif Kabupaten Subang oleh Universitas Subang.</p>
        </div>

        <table class="w-full mb-4 text-left align-top">
            <tr>
                <td class="w-6 align-top">1.</td>
                <td class="w-56 align-top">Materi Ujian</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top">{{ $report->exam_materials ?? 'Kebangsaan, Pancasila, UUD 1945, Pemerintahan Desa, dan Perundang-undangan Desa' }}</td>
            </tr>
            <tr>
                <td class="w-6 align-top">2.</td>
                <td class="w-56 align-top">Jumlah Peserta Terdaftar</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top">{{ $item['total_count'] }} Orang</td>
            </tr>
            <tr>
                <td class="w-6 align-top">3.</td>
                <td class="w-56 align-top">Jumlah Peserta Hadir</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top">{{ $item['present_count'] }} Orang</td>
            </tr>
            <tr>
                <td class="w-6 align-top">4.</td>
                <td class="w-56 align-top">Jumlah Peserta Tidak Hadir</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top">{{ $item['absent_count'] }} Orang</td>
            </tr>
        </table>

        <p class="mb-2">Rekapitulasi perolehan nilai hasil ujian Computer Assisted Test (CAT) peserta adalah sebagai berikut:</p>

        <table class="w-full table-bordered text-center mb-6">
            <thead>
                <tr>
                    <th class="w-10">No</th>
                    <th class="w-32">Nomor Peserta</th>
                    <th>Nama Lengkap Calon</th>
                    <th class="w-36">Desa / Kecamatan</th>
                    @if(!empty($isCombinedSession))
                        <th class="w-28">Ruang / Lab</th>
                    @endif
                    <th class="w-24">Nilai CAT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item['sessions'] as $index => $session)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $session->user->participant_number ?? $session->user->nik }}</td>
                    <td class="text-left px-2">{{ $session->user->name }}</td>
                    <td class="text-left px-2">{{ ucwords(strtolower($item['village'])) }}</td>
                    @if(!empty($isCombinedSession))
                        <td class="text-center px-1 font-semibold text-xs">{{ $session->exam->location ?? '-' }}</td>
                    @endif
                    <td>{{ rtrim(rtrim(number_format($session->score, 2), '0'), '.') }}</td>
                </tr>
                @endforeach
                @if(count($item['sessions']) == 0)
                <tr>
                    <td colspan="{{ !empty($isCombinedSession) ? 6 : 5 }}" class="py-4 text-gray-500">Belum ada data nilai peserta untuk instansi ini.</td>
                </tr>
                @endif
            </tbody>
        </table>

        <p class="mb-10 text-justify indent-10">Demikian Berita Acara ini dibuat dengan sebenarnya dalam rangkap secukupnya, ditandatangani oleh pihak penyelenggara dan saksi-saksi untuk dipergunakan sebagaimana mestinya.</p>

        <!-- Signatures -->
        <div class="break-inside-avoid">
            <div class="text-center font-bold mb-6">Yang Membuat Berita Acara</div>
            
            <div class="flex justify-between mb-8">
                <div class="text-center w-1/2">
                    <p>Mengetahui</p>
                    <p>Penanggungjawab</p>
                    <div class="h-20"></div>
                    <p class="font-bold"><u>{{ $report->supervisor_name ?? 'Dr. Drs. H. Komir Bastaman, S.H., M.Si.' }}</u></p>
                </div>
                <div class="text-center w-1/2">
                    <p>Subang, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                    <p>Panitia Seleksi</p>
                    <div class="h-20"></div>
                    <p class="font-bold"><u>{{ $report->committee_name ?? 'Kasda, S.T., M.T.' }}</u></p>
                </div>
            </div>

            <div class="mt-4 break-inside-avoid">
                <p class="font-bold text-center mb-4">Disaksikan oleh :</p>
                <table class="w-full text-sm">
                    <tbody>
                        @php
                            $witnesses = [
                                $report->witness_1 ?? 'Dr. Drs. H. Komir Bastaman, S.H., M.Si.',
                                $report->witness_2 ?? 'Dr. Ujang Charda S., S.H., M.H., M.I.P., M.A.P.',
                                $report->witness_3 ?? 'Dr. Moh. Asep Suharna, S.H., S.Pd., M.H.',
                                $report->witness_4 ?? 'Dr. Hj. Silvy Sondari Ghadzali, S.Psi., M.M.',
                                $report->witness_5 ?? 'Kasda, S.T., M.T.',
                                $report->witness_6 ?: 'Dr. Bety Miliyawati, S.Pd., M.Pd.',
                                $report->witness_7 ?: 'Dody Wahyudi Purnama, S.Pd., M.Pd.',
                            ];
                            $witnesses = array_filter($witnesses);
                        @endphp
                        @foreach($witnesses as $index => $witness)
                        <tr>
                            <td class="w-8 pb-3 align-bottom">{{ $index + 1 }}.</td>
                            <td class="w-[300px] pb-3 align-bottom">{{ $witness }}</td>
                            <td class="w-4 pb-3 align-bottom">:</td>
                            <td class="pb-3 border-b border-dotted border-gray-400 w-[200px]"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

</body>
</html>
