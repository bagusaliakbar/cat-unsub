<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen Naskah Bank Soal - CAT Universitas Subang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 sm:p-6 bg-slate-100">
    <div class="bg-white max-w-lg w-full rounded-2xl shadow-xl overflow-hidden border border-gray-100 my-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white p-6 sm:p-8 text-center relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg ring-4 ring-emerald-500/30">
                <svg class="w-9 h-9 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <span class="inline-block px-3 py-1 bg-white/20 text-white rounded-full text-xs font-bold tracking-wider uppercase mb-1">
                Sistem CAT LPPM Universitas Subang
            </span>
            <h1 class="text-2xl font-black tracking-wide">DOKUMEN VALID</h1>
            <p class="text-emerald-100 text-sm mt-1">Terverifikasi Otentik Diterbitkan oleh Sistem</p>
        </div>

        <!-- Body Details -->
        <div class="p-6 sm:p-8 space-y-4">
            <!-- Token Box -->
            <div class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-4 text-center">
                <span class="text-xs uppercase tracking-wider font-semibold text-emerald-700 block">Nomor Token Dokumen</span>
                <span class="text-lg sm:text-xl font-mono font-extrabold text-emerald-900 tracking-wider select-all">{{ $verification->token }}</span>
            </div>

            <div class="divide-y divide-gray-100 text-sm">
                <div class="py-2.5 flex justify-between items-center gap-3">
                    <span class="text-gray-500 font-medium shrink-0">Jenis Dokumen</span>
                    <span class="font-bold text-gray-900 text-right">{{ $verification->title }}</span>
                </div>

                <div class="py-2.5 flex justify-between items-center gap-3">
                    <span class="text-gray-500 font-medium shrink-0">Kategori / Paket</span>
                    <span class="font-bold text-blue-700 text-right bg-blue-50 px-2 py-0.5 rounded border border-blue-200 text-xs font-mono">
                        {{ $verification->category_name ?: 'Semua Kategori' }}
                    </span>
                </div>

                <div class="py-2.5 flex justify-between items-center gap-3">
                    <span class="text-gray-500 font-medium shrink-0">Jumlah Butir Soal</span>
                    <span class="font-bold text-gray-900 text-right">{{ $verification->total_questions }} Butir Soal</span>
                </div>

                <div class="py-2.5 flex justify-between items-center gap-3">
                    <span class="text-gray-500 font-medium shrink-0">Format Cetak</span>
                    <span class="font-semibold text-right {{ $verification->mode === 'with_keys' ? 'text-amber-700' : 'text-slate-700' }}">
                        {{ $verification->mode === 'with_keys' ? 'Master Soal (+ Kunci Jawaban & Poin)' : 'Naskah Ujian (Tanpa Kunci Jawaban)' }}
                    </span>
                </div>

                <div class="py-2.5 flex justify-between items-center gap-3">
                    <span class="text-gray-500 font-medium shrink-0">Dicetak Oleh</span>
                    <span class="font-bold text-gray-900 text-right">{{ $verification->printed_by ?: 'Administrator' }}</span>
                </div>

                <div class="py-2.5 flex justify-between items-center gap-3">
                    <span class="text-gray-500 font-medium shrink-0">Waktu Pencetakan</span>
                    <span class="font-semibold text-gray-800 text-right">
                        {{ \Carbon\Carbon::parse($verification->created_at)->locale('id')->translatedFormat('d F Y, H:i:s') }} WIB
                    </span>
                </div>

                @if(!empty($verification->checksum))
                <div class="py-2.5">
                    <span class="text-gray-500 font-medium text-xs block mb-1">Integritas Hash (Checksum)</span>
                    <span class="font-mono text-[10px] text-gray-600 bg-gray-50 p-2 rounded block break-all border border-gray-200 select-all">
                        {{ $verification->checksum }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Jaminan Keaslian -->
            <div class="p-3.5 bg-blue-50 border border-blue-200 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-xs text-blue-900 leading-relaxed">
                    Dokumen ini resmi dihasilkan langsung oleh <strong>Server CAT LPPM Universitas Subang</strong>. Segala bentuk perubahan fisik atau ketidakcocokan naskah dengan rekaman sistem ini dinyatakan <strong>TIDAK SAH</strong>.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 p-4 border-t border-gray-100 text-center text-xs text-gray-500 space-y-1">
            <p class="font-semibold text-gray-700">Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)</p>
            <p>Universitas Subang &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
