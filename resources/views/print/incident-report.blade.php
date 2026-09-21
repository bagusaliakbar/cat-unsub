<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kejadian Khusus - {{ $exam->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4;
            margin: 1cm 2cm 2cm 2cm;
        }
        @media print {
            body {
                width: 210mm;
                height: 297mm;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .a4-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: white;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        @media print {
            .a4-container {
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }
        
        .header-image {
            width: 100%;
            display: block;
            margin-bottom: 20px;
        }
        @media screen {
            .editable:empty:before {
                content: attr(data-placeholder);
                color: #9ca3af;
                font-style: italic;
            }
            .editable:hover, .editable:focus {
                background-color: #fef3c7 !important;
                outline: 1px dashed #d97706;
                border-color: transparent !important;
            }
        }
        .editable {
            outline: none;
            min-height: 1.5rem;
            cursor: text;
        }
        .lined-paper {
            background-image: repeating-linear-gradient(transparent, transparent 31px, #000 31px, #000 32px);
            line-height: 32px;
            min-height: 128px;
            width: 100%;
        }
    </style>
</head>
<body class="bg-gray-100">
    
    <!-- Tombol Cetak -->
    <div class="no-print fixed top-5 right-5 z-50">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg flex items-center transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak (Print)
        </button>
    </div>

    <div class="a4-container relative">
        <!-- Kop Surat Menggunakan Gambar -->
        <img src="{{ asset('images/kop-unsub.jpg') }}" alt="Kop Surat UNSUB" class="header-image">
        
        <h3 class="text-center font-bold text-lg mb-6 tracking-wide">FORM KEJADIAN KHUSUS</h3>
        
        <!-- Informasi Form -->
        <table class="w-full mb-6">
            <tr>
                <td class="w-48 font-bold align-top">Kegiatan</td>
                <td class="w-4 text-center align-top">:</td>
                <td class="align-top">{{ ucwords(strtolower($exam->title)) }}</td>
            </tr>
            <tr>
                <td class="font-bold align-top">Tanggal</td>
                <td class="text-center align-top">:</td>
                <td class="align-top">{{ $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->isoFormat('D MMMM Y') : \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="font-bold align-top">Waktu Kejadian</td>
                <td class="text-center align-top">:</td>
                <td class="align-top border-b border-black"><div contenteditable="true" class="editable w-full h-full" data-placeholder="Ketik waktu kejadian..."></div></td>
            </tr>
            <tr>
                <td class="font-bold align-top">Nomor Peserta</td>
                <td class="text-center align-top">:</td>
                <td class="align-top border-b border-black"><div contenteditable="true" class="editable w-full h-full" data-placeholder="Ketik nomor peserta..."></div></td>
            </tr>
            <tr>
                <td class="font-bold align-top">Nama Peserta</td>
                <td class="text-center align-top">:</td>
                <td class="align-top border-b border-black"><div contenteditable="true" class="editable w-full h-full" data-placeholder="Ketik nama peserta..."></div></td>
            </tr>
            <tr>
                <td class="font-bold align-top">Nomor Komputer</td>
                <td class="text-center align-top">:</td>
                <td class="align-top border-b border-black"><div contenteditable="true" class="editable w-full h-full" data-placeholder="Ketik nomor komputer..."></div></td>
            </tr>
            <tr>
                <td class="font-bold align-top">Jenis Kejadian</td>
                <td class="text-center align-top">:</td>
                <td class="align-top border-b border-black"><div contenteditable="true" class="editable w-full h-full" data-placeholder="Ketik jenis kejadian..."></div></td>
            </tr>
        </table>
        
        <!-- Kronologi -->
        <div class="mb-6">
            <h4 class="font-bold mb-2">Kronologi Kejadian</h4>
            <div contenteditable="true" class="editable lined-paper" data-placeholder="Ketik kronologi kejadian di sini..."></div>
        </div>
        
        <!-- Tindakan dan Keputusan -->
        <h4 class="font-bold italic mb-2">Tabel VI.3. Tindakan dan Keputusan</h4>
        <table class="w-full border-collapse border border-black mb-10 text-sm">
            <thead>
                <tr class="bg-gray-200" style="-webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: #d1d5db;">
                    <th class="border border-black px-4 py-2 w-1/3 text-center">Aspek</th>
                    <th class="border border-black px-4 py-2 w-2/3 text-center">Uraian</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-black px-4 py-3">Tindakan Pengawas</td>
                    <td class="border border-black px-4 py-3"><div contenteditable="true" class="editable w-full h-full" data-placeholder="Ketik tindakan pengawas..."></div></td>
                </tr>
                <tr>
                    <td class="border border-black px-4 py-3">Tindakan Operator CAT</td>
                    <td class="border border-black px-4 py-3"><div contenteditable="true" class="editable w-full h-full" data-placeholder="Ketik tindakan operator..."></div></td>
                </tr>
                <tr>
                    <td class="border border-black px-4 py-3">Keputusan Panitia</td>
                    <td class="border border-black px-4 py-3"><div contenteditable="true" class="editable w-full h-full">[SESUAI KETETAPAN PANITIA]</div></td>
                </tr>
                <tr>
                    <td class="border border-black px-4 py-3">Dampak terhadap Peserta</td>
                    <td class="border border-black px-4 py-3"><div contenteditable="true" class="editable w-full h-full" data-placeholder="Ketik dampak terhadap peserta..."></div></td>
                </tr>
                <tr>
                    <td class="border border-black px-4 py-3">Saksi/Petugas Lain yang Hadir</td>
                    <td class="border border-black px-4 py-3"><div contenteditable="true" class="editable w-full h-full" data-placeholder="Ketik daftar saksi..."></div></td>
                </tr>
            </tbody>
        </table>
        
        <!-- Signatures (Penanggungjawab & Panitia) -->
        <div class="break-inside-avoid mb-8">
            <div class="flex justify-between">
                <div class="text-center w-1/2">
                    <p>Mengetahui</p>
                    <p>Penanggungjawab</p>
                    <div class="h-20"></div>
                    <p class="font-bold">Dr. Drs. H. Komir Bastaman, SH, M.Si</p>
                </div>
                <div class="text-center w-1/2">
                    <p>Subang, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                    <p>Panitia Seleksi</p>
                    <div class="h-20"></div>
                    <p class="font-bold">Dr. Ujang Charda S., S.H.,M.H., M.IP., M.AP</p>
                </div>
            </div>
        </div>

        <!-- Saksi-Saksi -->
        <div class="break-inside-avoid">
            <p class="text-center mb-6">Disaksikan oleh :</p>
            <table class="w-full pl-8 max-w-2xl mx-auto">
                <tr>
                    <td class="w-8 py-1.5 align-bottom">1.</td>
                    <td class="py-1.5 align-bottom">Dr. Drs. H. Komir Bastaman, SH, M.Si</td>
                    <td class="w-4 py-1.5 align-bottom text-right">:</td>
                    <td class="py-1.5 align-bottom border-b-2 border-dotted border-black w-48"></td>
                </tr>
                <tr>
                    <td class="py-1.5 align-bottom">2.</td>
                    <td class="py-1.5 align-bottom">Dr. Ujang Charda S., SH., MH.,M.IP.,M.AP</td>
                    <td class="py-1.5 align-bottom text-right">:</td>
                    <td class="py-1.5 align-bottom border-b-2 border-dotted border-black"></td>
                </tr>
                <tr>
                    <td class="py-1.5 align-bottom">3.</td>
                    <td class="py-1.5 align-bottom">Moh. Asep Suharna, S.H., S.Pd., M.H.</td>
                    <td class="py-1.5 align-bottom text-right">:</td>
                    <td class="py-1.5 align-bottom border-b-2 border-dotted border-black"></td>
                </tr>
                <tr>
                    <td class="py-1.5 align-bottom">4.</td>
                    <td class="py-1.5 align-bottom">Dr. Hj. Silvy Sondari Ghadzali, S.Psi., M.M.</td>
                    <td class="py-1.5 align-bottom text-right">:</td>
                    <td class="py-1.5 align-bottom border-b-2 border-dotted border-black"></td>
                </tr>
                <tr>
                    <td class="py-1.5 align-bottom">5.</td>
                    <td class="py-1.5 align-bottom">Kasda, S.T., M.T.</td>
                    <td class="py-1.5 align-bottom text-right">:</td>
                    <td class="py-1.5 align-bottom border-b-2 border-dotted border-black"></td>
                </tr>
                <tr>
                    <td class="py-1.5 align-bottom">6.</td>
                    <td class="py-1.5 align-bottom">Dr. Bety Miliyawati, S.Pd., M.Pd.</td>
                    <td class="py-1.5 align-bottom text-right">:</td>
                    <td class="py-1.5 align-bottom border-b-2 border-dotted border-black"></td>
                </tr>
                <tr>
                    <td class="py-1.5 align-bottom">7.</td>
                    <td class="py-1.5 align-bottom">Dody Wahyudi Purnama, S.Pd., M.Pd</td>
                    <td class="py-1.5 align-bottom text-right">:</td>
                    <td class="py-1.5 align-bottom border-b-2 border-dotted border-black"></td>
                </tr>
            </table>
        </div>
        
    </div>
</body>
</html>
