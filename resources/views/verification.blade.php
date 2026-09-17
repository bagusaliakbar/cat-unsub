<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kartu Peserta - {{ $participant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white max-w-md w-full rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-blue-600 text-white p-6 text-center">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h1 class="text-2xl font-bold tracking-wider">DATA VALID</h1>
            <p class="text-blue-100 mt-1 text-sm">Peserta Resmi Ujian CAT</p>
        </div>

        <!-- Body -->
        <div class="p-6">
            <div class="flex justify-center mb-6">
                <img src="{{ $participant->profile_photo_path ? asset('storage/' . $participant->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($participant->name) . '&color=1d4ed8&background=eff6ff' }}" alt="Foto Peserta" class="w-24 h-32 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Nomor Peserta / Token</p>
                    <p class="text-lg font-bold text-gray-800">{{ $participant->participant_number }}</p>
                </div>
                
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Nama Lengkap</p>
                    <p class="text-base font-bold text-gray-800">{{ strtoupper($participant->name) }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">NIK / Username</p>
                    <p class="text-base font-semibold text-gray-700">{{ $participant->nik }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Asal Instansi / Pendidikan</p>
                    <p class="text-base font-semibold text-gray-700">{{ $participant->institution ?? '-' }}</p>
                </div>
                
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 p-4 border-t border-gray-100 text-center text-xs text-gray-500">
            Terverifikasi oleh Sistem CAT LPPM Universitas Subang <br>
            {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}
        </div>
    </div>
</body>
</html>
