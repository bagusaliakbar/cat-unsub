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
                <!-- Data Otomatis -->
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 space-y-4 md:col-span-2">
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Rekap Kehadiran (Otomatis)
                    </h4>
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
                    <p class="text-xs text-gray-500 italic mt-2">*Jumlah kehadiran dihitung otomatis berdasarkan jumlah peserta yang memulai ujian pada sistem.</p>
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

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="village" class="block text-sm font-semibold text-gray-700 mb-2">Desa <span class="text-red-500">*</span></label>
                            <input type="text" id="village" wire:model="village" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Masukkan desa...">
                            @error('village') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="district" class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                            <input type="text" id="district" wire:model="district" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Masukkan kecamatan...">
                            @error('district') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

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
                        <input type="text" id="committee_name" wire:model="committee_name" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Masukkan nama ketua panitia...">
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
                            <input type="text" id="witness_6" wire:model="witness_6" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm" placeholder="Nama saksi 6...">
                            @error('witness_6') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="witness_7" class="block text-xs font-semibold text-gray-700 mb-1">Saksi 7</label>
                            <input type="text" id="witness_7" wire:model="witness_7" class="w-full bg-gray-50 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm" placeholder="Nama saksi 7...">
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
