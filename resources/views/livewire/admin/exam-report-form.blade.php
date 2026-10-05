<x-slot name="header">
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.exams') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Berita Acara Pelaksanaan Ujian
        </h2>
    </div>
</x-slot>

<div class="w-full py-10 px-4 sm:px-6 lg:px-8 max-w-full">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-800 to-blue-600 px-6 py-5 text-white">
            <h3 class="text-lg font-bold">Formulir Berita Acara</h3>
            <p class="text-blue-100 text-sm mt-1">Ujian: {{ $exam->title }}</p>
        </div>

        <form wire:submit.prevent="saveAndPrint" class="p-6 md:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pilihan Cakupan Sesi & Ruangan (Multi-Lab Sibling Support) -->
                @if($has_sibling_exams)
                    <div class="bg-gradient-to-r from-emerald-50 via-teal-50/40 to-emerald-50 p-6 rounded-2xl border border-emerald-200 md:col-span-2 space-y-4 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-emerald-100 pb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-emerald-950 tracking-wide flex items-center gap-2">
                                        <span>CAKUPAN RUANGAN / SESI UJIAN</span>
                                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-200 text-emerald-900 border border-emerald-300">
                                            {{ $wave_name }}
                                        </span>
                                    </h4>
                                    <p class="text-xs text-emerald-800">
                                        Sesi ini dilaksanakan di beberapa ruangan berbeda: <strong>{{ $combined_locations }}</strong>. Pilih apakah ingin mencetak per ruangan atau rekap gabungan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 2 Card Radio Options -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Opsi Single Room -->
                            <label class="relative flex items-start p-4 rounded-xl border-2 cursor-pointer transition-all {{ $scope_mode === 'single' ? 'bg-white border-emerald-600 shadow-md ring-2 ring-emerald-500/20' : 'bg-emerald-50/50 border-emerald-200 hover:bg-white hover:border-emerald-300' }}">
                                <input type="radio" wire:model.live="scope_mode" value="single" class="mt-1 h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <div class="ml-3">
                                    <span class="block text-sm font-bold text-gray-900">
                                        Hanya Ruangan Ini: <span class="text-emerald-700 font-extrabold">{{ $exam->location ?? 'Lab Ini' }}</span>
                                    </span>
                                    <span class="block text-xs text-gray-600 mt-1 leading-relaxed">
                                        Mencetak Berita Acara khusus untuk peserta yang bertempat di ruangan <strong>{{ $exam->location }}</strong> saja (Laporan pengawas/proktor ruang).
                                    </span>
                                </div>
                            </label>

                            <!-- Opsi Combined Session -->
                            <label class="relative flex items-start p-4 rounded-xl border-2 cursor-pointer transition-all {{ $scope_mode === 'combined_session' ? 'bg-white border-emerald-600 shadow-md ring-2 ring-emerald-500/20' : 'bg-emerald-50/50 border-emerald-200 hover:bg-white hover:border-emerald-300' }}">
                                <input type="radio" wire:model.live="scope_mode" value="combined_session" class="mt-1 h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <div class="ml-3">
                                    <div class="flex items-center gap-2">
                                        <span class="block text-sm font-bold text-gray-900">
                                            Gabungan Semua Ruangan ({{ $wave_name }})
                                        </span>
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Rekomendasi Pleno
                                        </span>
                                    </div>
                                    <span class="block text-xs text-gray-600 mt-1 leading-relaxed">
                                        Menggabungkan seluruh peserta dari <strong>{{ $combined_locations }}</strong> ke dalam 1 dokumen Berita Acara Pleno Sesi resmi.
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>
                @endif

                <!-- Pilihan Lingkup Berita Acara (Instansi) -->
                <div class="bg-gradient-to-r from-blue-50 via-indigo-50/50 to-blue-50 p-6 rounded-2xl border border-blue-200 md:col-span-2 space-y-4 shadow-sm">
                    <!-- Card Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-blue-100 pb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-blue-900 tracking-wide">
                                    PILIHAN LINGKUP & FORMAT BERITA ACARA
                                </h4>
                                <p class="text-xs text-blue-700">
                                    Tentukan cakupan data peserta dan format halaman berita acara yang akan dicetak.
                                </p>
                            </div>
                        </div>
                        @if(count($institutions) > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 self-start sm:self-auto shrink-0 border border-blue-200">
                                {{ count($institutions) }} Desa / Instansi
                            </span>
                        @endif
                    </div>

                    <!-- Card Body / Selector Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                        <div class="md:col-span-4">
                            <label for="selected_institution" class="block text-xs font-bold text-blue-900 uppercase">
                                Format Dokumen Cetak:
                            </label>
                            <p class="text-xs text-blue-600 mt-0.5">
                                Pilih dokumen gabungan, satu desa spesifik, atau cetak semua terpisah.
                            </p>
                        </div>
                        <div class="md:col-span-8">
                            <select id="selected_institution" wire:model.live="selected_institution" class="w-full bg-white border border-blue-300 text-gray-800 text-sm font-semibold rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 shadow-sm transition-all">
                                <option value="all">Semua Instansi (1 Dokumen Gabungan)</option>
                                @if(count($institutions) > 1)
                                    <option value="all_separated">Cetak Semua Sekaligus (Pisah Lembar Per Instansi)</option>
                                @endif
                                @if(count($institutions) > 0)
                                    <optgroup label="Cetak Khusus Per Instansi:">
                                        @foreach($institutions as $inst)
                                            <option value="{{ $inst }}">Desa / Instansi: {{ $inst }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>
                    </div>
                    
                    <!-- Information Notice -->
                    @if($selected_institution && $selected_institution !== 'all' && $selected_institution !== 'all_separated')
                        <div class="text-xs text-blue-900 bg-blue-100/80 p-3 rounded-xl flex items-center border border-blue-200">
                            <svg class="w-4 h-4 mr-2.5 shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            <span>Sedang menampilkan Berita Acara khusus instansi <strong>{{ $selected_institution }}</strong>. Angka kehadiran & daftar nilai otomatis difilter khusus untuk peserta dari instansi ini.</span>
                        </div>
                    @elseif($selected_institution === 'all_separated')
                        <div class="text-xs text-indigo-900 bg-indigo-100/80 p-3 rounded-xl flex items-center border border-indigo-200">
                            <svg class="w-4 h-4 mr-2.5 shrink-0 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            <span><strong>Mode Batch Print (Multi-Halaman):</strong> Sistem akan menghasilkan Berita Acara resmi untuk setiap instansi secara otomatis terpisah per halaman (page break) dalam satu kali proses cetak.</span>
                        </div>
                    @else
                        <div class="text-xs text-blue-800 bg-blue-100/50 p-2.5 rounded-xl flex items-center border border-blue-200/60">
                            <svg class="w-4 h-4 mr-2.5 shrink-0 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            <span>Dokumen gabungan akan memuat seluruh peserta dari semua instansi dalam 1 tabel lengkap dengan kolom Desa/Instansi.</span>
                        </div>
                    @endif
                </div>

                <!-- Data Otomatis Rekap Kehadiran -->
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 space-y-4 md:col-span-2">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Rekap Kehadiran ({{ $selected_institution && $selected_institution !== 'all' && $selected_institution !== 'all_separated' ? $selected_institution : ($scope_mode === 'combined_session' ? 'Gabungan Sesi: ' . $combined_locations : 'Semua Peserta ' . ($exam->location ? '(' . $exam->location . ')' : '')) }})
                        </h4>
                        <span class="text-xs font-semibold text-gray-500">
                            Total Terdaftar: <strong class="text-gray-900">{{ $present_count + $absent_count }}</strong>
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
                            <span class="block text-3xl font-extrabold text-green-600">{{ $present_count }}</span>
                            <span class="block text-sm font-medium text-gray-500 mt-1">Peserta Hadir</span>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 text-center">
                            <span class="block text-3xl font-extrabold text-red-600">{{ $absent_count }}</span>
                            <span class="block text-sm font-medium text-gray-500 mt-1">Peserta Tidak Hadir</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 italic">*Jumlah kehadiran dihitung otomatis berdasarkan jumlah peserta yang memulai ujian pada sistem.</p>
                </div>

                <!-- Detail Pelaksanaan -->
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 space-y-4">
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        Detail Pelaksanaan
                    </h4>

                    <div>
                        <label for="reference_number" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Surat / Berita Acara</label>
                        <input type="text" id="reference_number" wire:model="reference_number" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Contoh: 001/BA-CAT/LPPM-UNSUB/2026">
                        @error('reference_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    @if($selected_institution === 'all')
                        <!-- Mode Semua Instansi (Gabungan) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Desa / Instansi</label>
                                <div class="w-full bg-gray-100 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl p-3 flex items-center justify-between">
                                    <span>Semua Desa (Gabungan)</span>
                                    <span class="text-xs bg-white text-gray-700 font-bold px-2 py-0.5 rounded border border-gray-200">{{ count($institutions) }} Desa</span>
                                </div>
                            </div>
                            <div>
                                <label for="district" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Kecamatan <span class="text-xs font-normal text-gray-400">(Opsional)</span>
                                </label>
                                <input type="text" id="district" wire:model="district" class="w-full bg-white border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 text-sm" placeholder="Kosongkan jika beda kecamatan">
                            </div>
                        </div>
                    @elseif($selected_institution === 'all_separated')
                        <!-- Mode Cetak Semua Terpisah Per Lembar -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Desa / Instansi</label>
                                <div class="w-full bg-indigo-50 border border-indigo-200 text-indigo-900 text-sm font-medium rounded-xl p-3 flex items-center justify-between">
                                    <span>Otomatis Per Desa</span>
                                    <span class="text-xs bg-white text-indigo-700 font-bold px-2 py-0.5 rounded border border-indigo-200">{{ count($institutions) }} Lembar</span>
                                </div>
                            </div>
                            <div>
                                <label for="district" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Kecamatan <span class="text-xs font-normal text-gray-400">(Opsional)</span>
                                </label>
                                <input type="text" id="district" wire:model="district" class="w-full bg-white border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 text-sm" placeholder="Kosongkan jika beda kecamatan">
                            </div>
                        </div>
                    @else
                        <!-- Mode Khusus 1 Desa -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="village" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Desa <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="village" wire:model="village" class="w-full bg-white border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 text-sm" placeholder="Nama desa...">
                                @error('village') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="district" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Kecamatan <span class="text-xs font-normal text-gray-400">(Opsional)</span>
                                </label>
                                <input type="text" id="district" wire:model="district" class="w-full bg-white border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 text-sm" placeholder="Nama kecamatan...">
                                @error('district') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="exam_materials" class="block text-sm font-semibold text-gray-700 mb-2">Materi Ujian</label>
                        <textarea id="exam_materials" wire:model="exam_materials" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Masukkan materi ujian..."></textarea>
                        @error('exam_materials') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Catatan Kejadian Selama Ujian (Opsional)</label>
                        <textarea id="notes" wire:model="notes" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Misal: Terdapat pemadaman listrik selama 10 menit..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika ujian berjalan lancar tanpa kendala.</p>
                        @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Penanggung Jawab & Panitia -->
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 space-y-4">
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Penanggung Jawab & Panitia
                    </h4>

                    <div>
                        <label for="supervisor_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Penanggung Jawab <span class="text-red-500">*</span></label>
                        <input type="text" id="supervisor_name" wire:model="supervisor_name" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Masukkan nama proktor...">
                        @error('supervisor_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="committee_name" class="block text-sm font-semibold text-gray-700 mb-2">Ketua Panitia Seleksi <span class="text-red-500">*</span></label>
                        <input type="text" id="committee_name" wire:model="committee_name" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Kasda, S.T., M.T.">
                        @error('committee_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Daftar Saksi-saksi -->
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 space-y-4 md:col-span-2">
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Daftar Saksi-saksi
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="witness_1" class="block text-xs font-semibold text-gray-700 mb-1">Saksi 1</label>
                            <input type="text" id="witness_1" wire:model="witness_1" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm" placeholder="Nama saksi 1...">
                            @error('witness_1') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="witness_2" class="block text-xs font-semibold text-gray-700 mb-1">Saksi 2</label>
                            <input type="text" id="witness_2" wire:model="witness_2" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm" placeholder="Nama saksi 2...">
                            @error('witness_2') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="witness_3" class="block text-xs font-semibold text-gray-700 mb-1">Saksi 3</label>
                            <input type="text" id="witness_3" wire:model="witness_3" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm" placeholder="Nama saksi 3...">
                            @error('witness_3') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="witness_4" class="block text-xs font-semibold text-gray-700 mb-1">Saksi 4</label>
                            <input type="text" id="witness_4" wire:model="witness_4" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm" placeholder="Nama saksi 4...">
                            @error('witness_4') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="witness_5" class="block text-xs font-semibold text-gray-700 mb-1">Saksi 5</label>
                            <input type="text" id="witness_5" wire:model="witness_5" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm" placeholder="Nama saksi 5...">
                            @error('witness_5') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="witness_6" class="block text-xs font-semibold text-gray-700 mb-1">Saksi 6</label>
                            <input type="text" id="witness_6" wire:model="witness_6" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm" placeholder="Dr. Bety Miliyawati, S.Pd., M.Pd.">
                            @error('witness_6') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="witness_7" class="block text-xs font-semibold text-gray-700 mb-1">Saksi 7</label>
                            <input type="text" id="witness_7" wire:model="witness_7" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm" placeholder="Dody Wahyudi Purnama, S.Pd., M.Pd.">
                            @error('witness_7') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition-all flex items-center transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Simpan & Cetak Berita Acara
                </button>
            </div>
        </form>
    </div>
</div>
