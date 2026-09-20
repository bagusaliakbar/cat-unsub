<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara - {{ $exam->title }}</title>
    <!-- Tailwind CSS (compiled or CDN for print) -->
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
            padding: 4px 8px;
        }
        table.table-bordered th {
            background-color: #203864 !important; /* Dark Blue from screenshot */
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
    <div class="text-center mb-6">
        <h3 class="text-lg font-bold uppercase">BERITA ACARA HASIL SELEKSI TERTULIS BERBASIS CAT</h3>
        <h3 class="text-lg font-bold uppercase">CALON KEPALA DESA ANTAR WAKTU (PAW) DESA {{ strtoupper($report->village ?? '[NAMA DESA]') }}</h3>
        <h3 class="text-lg font-bold uppercase">KECAMATAN {{ strtoupper($report->district ?? '[NAMA KECAMATAN]') }} KABUPATEN SUBANG</h3>
        <p class="mt-1">Nomor: {{ $report->reference_number ?? '[Nomor Surat]' }}</p>
    </div>

    <!-- Content -->
    <div class="text-justify mb-4">
        <p class="indent-10">Pada hari ini, <strong>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l') }}</strong> tanggal <strong>{{ \Carbon\Carbon::now()->translatedFormat('d') }}</strong> bulan <strong>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F') }}</strong> tahun <strong>{{ ucwords(\NumberFormatter::create('id_ID', \NumberFormatter::SPELLOUT)->format(\Carbon\Carbon::now()->year)) }}</strong>, bertempat di Laboratorium Komputer Universitas Subang, telah dilaksanakan Ujian Penyaringan Seleksi Tertulis berbasis Computer Assisted Test (CAT) bagi Calon Kepala Desa Antar Waktu (PAW) Desa {{ ucwords(strtolower($report->village ?? '[Nama Desa]')) }} Kecamatan {{ ucwords(strtolower($report->district ?? '[Nama Kecamatan]')) }} Kabupaten Subang oleh Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM) Universitas Subang.</p>
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
            <td class="align-top">{{ $report->present_count + $report->absent_count }} Orang</td>
        </tr>
        <tr>
            <td class="w-6 align-top">3.</td>
            <td class="w-56 align-top">Jumlah Peserta Hadir</td>
            <td class="w-4 text-center align-top">:</td>
            <td class="align-top">{{ $report->present_count }} Orang</td>
        </tr>
        <tr>
            <td class="w-6 align-top">4.</td>
            <td class="w-56 align-top">Jumlah Peserta Tidak Hadir</td>
            <td class="w-4 text-center align-top">:</td>
            <td class="align-top">{{ $report->absent_count }} Orang</td>
        </tr>
    </table>

    <p class="mb-2">Rekapitulasi perolehan nilai hasil ujian Computer Assisted Test (CAT) peserta adalah sebagai berikut:</p>

    <table class="w-full table-bordered text-center mb-6">
        <thead>
            <tr>
                <th class="w-10">No</th>
                <th class="w-32">Nomor Peserta</th>
                <th>Nama Lengkap Calon</th>
                <th class="w-40">Desa</th>
                <th class="w-24">Nilai CAT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessions as $index => $session)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $session->user->participant_number ?? $session->user->nik }}</td>
                <td class="text-left px-2">{{ $session->user->name }}</td>
                <td class="text-left px-2">{{ ucwords(strtolower($report->village ?? '')) }}</td>
                <td>{{ rtrim(rtrim(number_format($session->score, 2), '0'), '.') }}</td>
            </tr>
            @endforeach
            @if($sessions->count() == 0)
            <tr>
                <td colspan="5" class="py-4">Belum ada data nilai peserta.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <p class="mb-10 text-justify indent-10">Demikian Berita Acara ini dibuat dengan sebenarnya dalam rangkap secukupnya, ditandatangani oleh pihak penyelenggara dan saksi-saksi untuk dipergunakan sebagaimana mestinya.</p>

    <!-- Signatures -->
    <div>
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
                <p class="font-bold"><u>{{ $report->committee_name ?? 'Dr. Ujang Charda S., S.H., M.H., M.I.P., M.A.P.' }}</u></p>
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
                            $report->witness_6 ?? '',
                            $report->witness_7 ?? '',
                        ];
                        $witnesses = array_filter($witnesses); // Remove empty witnesses
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

    <!-- Print Button (Hidden on Print) -->
    <div class="fixed bottom-8 right-8 no-print flex space-x-4">
        <a href="{{ route('admin.exams') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow-lg font-bold">
            Tutup
        </a>
        <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-lg font-bold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak (Print)
        </button>
    </div>

</body>
</html>
