<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Bank Soal CAT - {{ $categoryTitle }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0 !important;
                margin: 0 !important;
                background-color: white !important;
                font-size: 10.5pt;
                color: #000 !important;
            }
            .page-break {
                page-break-after: always;
            }
            .break-inside-avoid {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            @page {
                size: A4;
                margin: 12mm 15mm 15mm 15mm;
            }
        }
        body {
            font-family: 'Times New Roman', Times, serif, system-ui;
            color: #111827;
        }
        .sans-font {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="p-4 sm:p-8 max-w-4xl mx-auto bg-white text-black text-[11pt]" onload="window.print()">

    <!-- Floating Toolbar (Hidden on Print) -->
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 no-print bg-white/95 backdrop-blur-md shadow-2xl border border-gray-200 px-5 py-2.5 rounded-2xl flex items-center space-x-3 z-50 sans-font text-xs">
        <a href="{{ route('admin.questions') }}" class="font-semibold text-gray-700 hover:text-blue-600 flex items-center transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <span class="text-gray-300">|</span>

        <!-- Filter Kategori -->
        <div class="flex items-center space-x-1.5">
            <label class="font-bold text-gray-700">Kategori:</label>
            <select onchange="window.location.href = this.value" class="font-medium border-gray-300 rounded-lg py-1 px-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="{{ route('admin.questions.print', ['category' => 'all', 'mode' => $mode, 'selected' => $selectedIds]) }}" {{ $categoryId === 'all' ? 'selected' : '' }}>
                    Semua Kategori ({{ $categories->count() }})
                </option>
                @foreach($categories as $cat)
                    <option value="{{ route('admin.questions.print', ['category' => $cat->id, 'mode' => $mode, 'selected' => $selectedIds]) }}" {{ (string)$categoryId === (string)$cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Mode Kunci Jawaban -->
        <div class="flex items-center space-x-1.5">
            <label class="font-bold text-gray-700">Kunci:</label>
            <select onchange="window.location.href = this.value" class="font-medium border-gray-300 rounded-lg py-1 px-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="{{ route('admin.questions.print', ['category' => $categoryId, 'mode' => 'without_keys', 'selected' => $selectedIds]) }}" {{ $mode === 'without_keys' ? 'selected' : '' }}>
                    Tanpa Kunci (Naskah Ujian)
                </option>
                <option value="{{ route('admin.questions.print', ['category' => $categoryId, 'mode' => 'with_keys', 'selected' => $selectedIds]) }}" {{ $mode === 'with_keys' ? 'selected' : '' }}>
                    ✓ Dengan Kunci (Master Soal)
                </option>
            </select>
        </div>

        @if(!empty($selectedIds))
            <span class="bg-indigo-100 text-indigo-800 font-bold px-2 py-0.5 rounded text-[11px] border border-indigo-200">
                {{ $totalQuestions }} Soal Dipilih
            </span>
        @endif

        <button onclick="window.print()" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold flex items-center shadow-sm transition">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print
        </button>
    </div>

    <!-- Header / Kop Surat -->
    <div class="mb-4 text-center">
        <img src="{{ asset('images/kop-unsub.jpg') }}" alt="Kop Universitas Subang" class="w-full mx-auto h-auto object-contain">
    </div>

    <!-- Judul Dokumen -->
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold uppercase tracking-wide">
            {{ $mode === 'with_keys' ? 'MASTER DOKUMEN BANK SOAL UJIAN CAT' : 'NASKAH BANK SOAL UJIAN CAT' }}
        </h2>
        <h3 class="text-base font-bold uppercase tracking-wide text-gray-800">
            KATEGORI: {{ strtoupper($categoryTitle) }}
        </h3>
    </div>

    <!-- Block Info Naskah & QR Code Verifikasi -->
    <div class="border-2 border-black rounded-lg p-3.5 mb-6 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-center gap-4 break-inside-avoid sans-font">
        <!-- Kolom Kiri: Metadata Dokumen -->
        <div class="flex-1 text-xs space-y-1.5">
            <div class="grid grid-cols-3 gap-1">
                <span class="font-bold text-gray-700">Kategori / Paket</span>
                <span class="col-span-2">: <strong>{{ $categoryTitle }}</strong></span>
            </div>
            <div class="grid grid-cols-3 gap-1">
                <span class="font-bold text-gray-700">Jumlah Butir Soal</span>
                <span class="col-span-2">: {{ $totalQuestions }} Butir Soal</span>
            </div>
            <div class="grid grid-cols-3 gap-1">
                <span class="font-bold text-gray-700">Format Dokumen</span>
                <span class="col-span-2">: 
                    <span class="font-semibold {{ $mode === 'with_keys' ? 'text-amber-700' : 'text-blue-700' }}">
                        {{ $mode === 'with_keys' ? 'Master Soal (+ Kunci Jawaban & Bobot Poin)' : 'Naskah Ujian (Tanpa Kunci Jawaban)' }}
                    </span>
                </span>
            </div>
            <div class="grid grid-cols-3 gap-1">
                <span class="font-bold text-gray-700">Waktu Cetak</span>
                <span class="col-span-2">: {{ \Carbon\Carbon::parse($verification->created_at)->locale('id')->translatedFormat('l, d F Y - H:i:s') }} WIB</span>
            </div>
            <div class="grid grid-cols-3 gap-1">
                <span class="font-bold text-gray-700">Petugas / Operator</span>
                <span class="col-span-2">: {{ $verification->printed_by ?: auth()->user()->name }}</span>
            </div>
        </div>

        <!-- Kolom Kanan: QR Code & Token Verifikasi -->
        <div class="flex flex-col items-center justify-center border-l-0 sm:border-l-2 border-black sm:pl-5 pt-3 sm:pt-0 shrink-0">
            <div class="p-1 bg-white border border-gray-300 rounded shadow-xs">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate(route('verify.document', $verification->token)) !!}
            </div>
            <span class="text-[10px] font-mono font-extrabold tracking-wider mt-1 text-gray-900">{{ $verification->token }}</span>
            <span class="text-[8px] text-gray-600 text-center leading-tight mt-0.5">
                Scan QR Code untuk verifikasi<br>keaslian naskah di Sistem CAT
            </span>
        </div>
    </div>

    <!-- Petunjuk Singkat -->
    @if($mode === 'without_keys')
    <div class="mb-5 p-2.5 border border-black text-xs italic bg-gray-50/30 break-inside-avoid">
        <strong>Petunjuk Umum:</strong> Pilihlah salah satu jawaban yang paling tepat dengan memberi tanda silang (X) atau menghitamkan bulatan pada huruf A, B, C, atau D pada lembar jawaban yang telah disediakan.
    </div>
    @endif

    <!-- Daftar Soal -->
    <div class="space-y-4">
        @forelse($questions as $index => $q)
            <div class="break-inside-avoid pb-3 border-b border-gray-200">
                <!-- Teks Soal -->
                <div class="flex items-start">
                    <span class="font-bold text-sm w-7 shrink-0 text-right pr-2">{{ $index + 1 }}.</span>
                    <div class="flex-1 text-sm text-gray-900 leading-relaxed">
                        <div class="font-medium inline">{!! nl2br(e($q->text)) !!}</div>
                        
                        @if($mode === 'with_keys')
                            <span class="ml-2 inline-flex items-center text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-blue-50 text-blue-800 border border-blue-200 align-middle">
                                {{ $q->points }} Poin
                            </span>
                            @if($q->difficulty)
                                @php
                                    $diffLabel = match($q->difficulty) {
                                        'easy' => 'Mudah',
                                        'medium' => 'Sedang',
                                        'hard' => 'Sulit',
                                        default => ucfirst($q->difficulty),
                                    };
                                @endphp
                                <span class="ml-1 inline-flex items-center text-[10px] font-sans font-medium px-1.5 py-0.5 rounded bg-gray-100 text-gray-700 align-middle">
                                    {{ $diffLabel }}
                                </span>
                            @endif
                            @if($q->category && $categoryId === 'all')
                                <span class="ml-1 inline-flex items-center text-[10px] font-sans font-medium px-1.5 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200 align-middle">
                                    {{ $q->category->name }}
                                </span>
                            @endif
                        @endif

                        @if(!empty($q->image_path))
                            <div class="my-2">
                                <img src="{{ asset('storage/' . $q->image_path) }}" alt="Gambar Soal {{ $index + 1 }}" class="max-h-48 rounded border border-gray-300 object-contain">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pilihan Jawaban -->
                @if($q->type === 'multiple_choice' && $q->options && $q->options->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-1 mt-2 ml-7 text-xs sans-font">
                        @foreach($q->options as $optIdx => $opt)
                            @php
                                $letter = chr(65 + $optIdx);
                                $isCorrect = (bool)$opt->is_correct;
                            @endphp
                            <div class="flex items-start p-1.5 rounded {{ $mode === 'with_keys' && $isCorrect ? 'bg-emerald-50 border border-emerald-400 font-bold text-emerald-950' : 'text-gray-800' }}">
                                <span class="w-5 shrink-0 font-bold {{ $mode === 'with_keys' && $isCorrect ? 'text-emerald-700' : 'text-gray-600' }}">
                                    {{ $letter }}.
                                </span>
                                <span class="flex-1">{{ $opt->text }}</span>
                                @if($mode === 'with_keys' && $isCorrect)
                                    <span class="ml-1.5 text-emerald-700 font-bold shrink-0 text-[11px]">✓ KUNCI</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @elseif($q->type === 'essay')
                    <div class="mt-2 ml-7">
                        @if($mode === 'with_keys')
                            <div class="p-2 bg-amber-50 border border-amber-200 rounded text-xs text-amber-900">
                                <strong>Kunci / Rubrik Penilaian:</strong> Soal Esai (Maksimal {{ $q->points }} Poin).
                            </div>
                        @else
                            <div class="h-16 border border-dashed border-gray-300 rounded p-2 text-xs text-gray-400 italic">
                                Lembar jawaban esai...
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                Tidak ada soal yang ditemukan untuk kriteria cetak ini.
            </div>
        @endforelse
    </div>

    <!-- Lembar Berita Acara / Pengesahan Naskah Soal -->
    @if($questions->count() > 0)
    <div class="break-inside-avoid mt-8 pt-5 border-t-2 border-black sans-font">
        <p class="text-xs text-gray-800 text-justify mb-6 leading-relaxed">
            <strong>Keterangan Pengesahan:</strong> Naskah bank soal di atas telah melalui proses telaah, verifikasi, dan diterbitkan secara resmi melalui <em>Sistem Computer Assisted Test (CAT) Universitas Subang</em> dengan nomor verifikasi otentik <strong>{{ $verification->token }}</strong>. Naskah ini sah digunakan sebagai instrumen seleksi dan dijaga kerahasiaannya sesuai dengan pakta integritas panitia seleksi.
        </p>

        <div class="flex justify-between items-start text-xs text-center mt-6">
            <div class="w-2/5">
                <p>Mengetahui,</p>
                <p class="font-bold">Penanggung Jawab Ujian CAT</p>
                <div class="h-20"></div>
                <p class="font-bold underline">Dr. Drs. H. Komir Bastaman, S.H., M.Si.</p>
            </div>

            <div class="w-2/5">
                <p>Subang, {{ \Carbon\Carbon::parse($verification->created_at)->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="font-bold">Ketua Panitia Seleksi</p>
                <div class="h-24"></div>
                <p class="font-bold underline">Kasda, S.T., M.T.</p>
            </div>
        </div>

        <!-- Security Footer -->
        <div class="mt-8 pt-2 border-t border-gray-300 flex justify-between items-center text-[9px] text-gray-500 font-mono">
            <span>SISTEM CAT UNIVERSITAS SUBANG</span>
            <span>TOKEN: {{ $verification->token }}</span>
            <span>CHECKSUM: {{ substr($verification->checksum, 0, 16) }}...</span>
        </div>
    </div>
    @endif

</body>
</html>
