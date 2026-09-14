<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manajemen Ujian
    </h2>
</x-slot>

<div class="w-full py-10 px-4 sm:px-6 lg:px-8">
    
    <!-- Breadcrumb -->
    <div class="flex items-center text-sm text-gray-600 font-medium space-x-2 mb-6 px-2 sm:px-0">
        <span class="text-blue-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Admin
        </span>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 font-bold text-base">Manajemen Ujian</span>
    </div>
    <!-- Premium Header -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-600 rounded-2xl shadow-lg p-6 mb-8 text-white flex flex-col md:flex-row items-center justify-between transition-all duration-300">
        <div>
            <h2 class="text-3xl font-bold tracking-tight mb-1 flex items-center">
                <svg class="w-8 h-8 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Manajemen Ujian
            </h2>
            <p class="text-blue-100 opacity-90 text-sm">Kelola jadwal, durasi, dan pengaturan ujian CAT.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <button wire:click="create()" class="bg-white text-blue-700 hover:bg-blue-50 focus:ring-4 focus:ring-blue-300 font-bold py-2.5 px-5 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Ujian Baru
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg shadow-sm mb-6 animate-pulse" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <!-- Premium Table Layout -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3 min-w-[250px]">Judul & Deskripsi</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Durasi</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Mulai</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-right w-1/4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($exams as $exam)
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="text-sm font-bold text-gray-900 group-hover:text-blue-700 transition-colors">{{ $exam->title }}</div>
                                    @if($exam->wave)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200 shrink-0">
                                            {{ $exam->wave->name }}
                                        </span>
                                    @endif
                                    @if($exam->location)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 shrink-0 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $exam->location }}
                                        </span>
                                    @endif
                                    @if($exam->is_simulation)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200 shrink-0 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                            Simulasi
                                        </span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $exam->description ?: 'Tidak ada deskripsi' }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-700 font-medium">
                                    <svg class="w-4 h-4 text-gray-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $exam->duration_minutes }} Menit
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                @if($exam->start_time)
                                    <div class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} WIB</div>
                                @else
                                    <span class="text-sm text-gray-400 font-medium">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                @if($exam->end_time)
                                    <div class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($exam->end_time)->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }} WIB</div>
                                @else
                                    <span class="text-sm text-gray-400 font-medium">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-sm {{ $exam->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 mt-1.5 {{ $exam->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    {{ $exam->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right text-sm font-medium">
                                <div class="flex flex-wrap items-center justify-end gap-2 max-w-[400px] ml-auto">
                                    <a href="{{ route('admin.exams.monitor', $exam->id) }}" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 p-1.5 rounded-lg transition-colors flex items-center" title="Monitor Ujian">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.exams.preview', $exam->id) }}" class="text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 p-1.5 rounded-lg transition-colors flex items-center" title="Preview Soal Ujian">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.exams.report', $exam->id) }}" class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 p-1.5 rounded-lg transition-colors flex items-center" title="Cetak Berita Acara">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.exams.attendance', $exam->id) }}" class="text-teal-600 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 p-1.5 rounded-lg transition-colors flex items-center" title="Cetak Daftar Hadir" target="_blank">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </a>
                                    <button type="button" wire:click.prevent="manageQuestions({{ $exam->id }})" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-1.5 rounded-lg transition-colors flex items-center" title="Kelola Soal Ujian">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    </button>
                                    <button type="button" wire:click.prevent="manageParticipants({{ $exam->id }})" class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 p-1.5 rounded-lg transition-colors flex items-center" title="Assign Peserta">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </button>
                                    <button type="button" wire:click.prevent="edit({{ $exam->id }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-1.5 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button wire:click="delete({{ $exam->id }})" wire:confirm="Apakah Anda yakin ingin menghapus ujian ini?" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-50 text-gray-400 p-4 rounded-full mb-4">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada ujian yang dibuat</h3>
                                    <p class="text-sm text-gray-500 mb-4">Mulai kelola sistem CAT dengan membuat ujian pertama Anda.</p>
                                    <button wire:click="create()" class="text-blue-600 font-bold hover:underline">Buat Ujian Baru &rarr;</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($exams->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $exams->links() }}
        </div>
        @endif
    </div>

    <!-- Modern Modal Form -->
    @if($isModalOpen)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal Panel -->
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800" id="modal-title">
                            {{ $exam_id ? 'Edit Ujian' : 'Buat Ujian Baru' }}
                        </h3>
                        <button wire:click="closeModal()" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full p-1.5 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form>
                        <div class="px-6 py-6 bg-white space-y-5 max-h-[70vh] overflow-y-auto">
                            
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-gray-700 text-sm font-semibold mb-2">Judul Ujian <span class="text-red-500">*</span></label>
                                <input type="text" id="title" wire:model="title" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Misal: Ujian Masuk Gelombang 1">
                                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>
                            
                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi (Opsional)</label>
                                <textarea id="description" wire:model="description" rows="2" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Tambahkan instruksi atau deskripsi ujian di sini..."></textarea>
                                @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-gray-700 text-sm font-semibold mb-2">Ruangan/Tempat Ujian (Opsional)</label>
                                <input type="text" id="location" wire:model="location" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Misal: Lab Komputer 1 / Ruang CBT">
                                @error('location') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <!-- Rules -->
                            <div>
                                <label for="rules" class="block text-gray-700 text-sm font-semibold mb-2">Tata Tertib Ujian (Opsional)</label>
                                <textarea id="rules" wire:model.live="rules" rows="4" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Masukkan poin-poin tata tertib (pisahkan dengan enter/baris baru)..."></textarea>
                                <p class="text-xs text-gray-500 mt-1 mb-2">Gunakan enter (baris baru) untuk memisahkan setiap poin tata tertib. Jika dikosongkan, sistem akan menggunakan tata tertib standar berikut:</p>
                                @error('rules') <span class="text-red-500 text-xs mt-1 block mb-2">{{ $message }}</span>@enderror
                                
                                @if(empty(trim($rules)))
                                    <div class="mt-2 bg-gray-50 rounded-xl p-4 border border-gray-200 text-left opacity-75">
                                        <div class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wide flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Preview: Tata Tertib Standar Sistem
                                        </div>
                                        <ul class="text-sm text-gray-700 space-y-3 list-none">
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span>Berdoalah sebelum mulai mengerjakan soal ujian.</span>
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span>Waktu akan <span class="font-bold text-gray-900">berjalan secara otomatis</span> setelah Anda menekan tombol mulai ujian.</span>
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                <span>Dilarang membuka <i>tab</i> browser lain, menutup browser, atau membuka aplikasi lain selama ujian berlangsung. (Sistem akan mendeteksi pelanggaran).</span>
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                <span>Dilarang keras melakukan kecurangan dalam bentuk apapun, termasuk bekerjasama atau menggunakan alat bantu pencarian.</span>
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                <span>Apabila waktu ujian habis, sistem akan <span class="font-bold text-gray-900">mengumpulkan jawaban Anda secara otomatis</span>.</span>
                                            </li>
                                        </ul>
                                    </div>
                                @else
                                    <div class="mt-2 bg-blue-50/50 rounded-xl p-4 border border-blue-200 text-left shadow-sm">
                                        <div class="text-xs font-bold text-blue-800 mb-3 uppercase tracking-wide flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Preview: Tata Tertib Kustom
                                        </div>
                                        <ul class="text-sm text-gray-700 space-y-3 list-none">
                                            @foreach(explode("\n", $rules) as $rule)
                                                @if(trim($rule) !== '')
                                                <li class="flex items-start">
                                                    <svg class="w-4 h-4 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <span>{!! nl2br(e(trim($rule))) !!}</span>
                                                </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <!-- Duration & Passing Grade -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="duration_minutes" class="block text-gray-700 text-sm font-semibold mb-2">Durasi (Menit) <span class="text-red-500">*</span></label>
                                    <input type="number" id="duration_minutes" wire:model="duration_minutes" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm">
                                    @error('duration_minutes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label for="passing_grade" class="block text-gray-700 text-sm font-semibold mb-2">Passing Grade (Batas Lulus) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.1" id="passing_grade" wire:model="passing_grade" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm">
                                    @error('passing_grade') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <!-- Time Schedule -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                                <div>
                                    <label for="start_time" class="block text-gray-700 text-sm font-semibold mb-2">Waktu Mulai (Opsional)</label>
                                    <input type="datetime-local" id="start_time" wire:model="start_time" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm">
                                    @error('start_time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label for="end_time" class="block text-gray-700 text-sm font-semibold mb-2">Waktu Selesai (Opsional)</label>
                                    <input type="datetime-local" id="end_time" wire:model="end_time" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm">
                                    @error('end_time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <!-- Wave Selection -->
                            <div>
                                <label for="wave_id" class="block text-gray-700 text-sm font-semibold mb-2">Gelombang Pelaksanaan (Opsional)</label>
                                <select id="wave_id" wire:model="wave_id" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm">
                                    <option value="">-- Tidak Terikat Gelombang --</option>
                                    @foreach($waves as $w)
                                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Jika dipilih, ujian ini ditujukan khusus untuk gelombang tertentu.</p>
                                @error('wave_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <!-- Randomization Options -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 bg-purple-50/50 p-4 rounded-xl border border-purple-100">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="randomize_questions" wire:model="randomize_questions" type="checkbox" class="w-4 h-4 text-purple-600 bg-white border-purple-300 rounded focus:ring-purple-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="randomize_questions" class="font-medium text-purple-900">Acak Urutan Soal</label>
                                        <p class="text-purple-600 text-xs mt-1">Soal akan ditampilkan dengan urutan acak untuk setiap peserta.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="randomize_options" wire:model="randomize_options" type="checkbox" class="w-4 h-4 text-purple-600 bg-white border-purple-300 rounded focus:ring-purple-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="randomize_options" class="font-medium text-purple-900">Acak Pilihan Jawaban</label>
                                        <p class="text-purple-600 text-xs mt-1">Opsi jawaban akan diacak (hanya berlaku untuk soal pilihan ganda).</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Token & Active Status -->
                            <div>
                                <label for="token" class="block text-gray-700 text-sm font-semibold mb-2">Token Ujian (Opsional)</label>
                                <input type="text" id="token" wire:model="token" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Tinggalkan kosong jika ujian ini tidak memerlukan token/PIN">
                                @error('token') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <div class="flex-1 flex items-center bg-blue-50 p-4 rounded-xl border border-blue-100">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        <span class="ml-3 text-sm font-bold text-gray-800">Aktifkan Ujian Ini Sekarang</span>
                                    </label>
                                </div>
                                
                                <div class="flex-1 flex items-center bg-purple-50 p-4 rounded-xl border border-purple-100">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="is_simulation" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        <span class="ml-3 text-sm font-bold text-gray-800">Tandai Sebagai Ujian Simulasi</span>
                                    </label>
                                </div>
                            </div>

                        </div>
                        
                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 sm:flex sm:flex-row-reverse rounded-b-2xl">
                            <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Ujian
                            </button>
                            <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Question Selection Modal for Exam -->
    @if($isQuestionModalOpen)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full border border-gray-100">
                    
                    <div class="bg-gradient-to-r from-blue-800 to-blue-600 px-6 py-4 border-b border-gray-100 flex justify-between items-center shrink-0">
                        <div class="text-white">
                            <h3 class="text-xl font-bold" id="modal-title">Pilih Soal dari Bank Soal</h3>
                            <p class="text-blue-100 text-sm mt-1">Ujian: {{ $managing_exam_title }}</p>
                        </div>
                        <button wire:click="closeQuestionModal()" class="text-blue-200 hover:text-white bg-blue-700 hover:bg-blue-800 rounded-full p-1.5 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <!-- Filter and Stats Header -->
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <!-- Filters -->
                            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                                <select wire:model.live="filter_category" class="bg-white border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-48 p-2.5 shadow-sm">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                
                                <!-- Type Filter -->
                                <select wire:model.live="filter_type" class="bg-white border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-40 p-2.5 shadow-sm">
                                    <option value="">Semua Jenis</option>
                                    <option value="multiple_choice">Pilihan Ganda</option>
                                    <option value="essay">Essay</option>
                                </select>
                                
                                <!-- Search -->
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input wire:model.live.debounce.300ms="search_question" type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 shadow-sm" placeholder="Cari soal...">
                                </div>
                            </div>
                            
                            <!-- Stats (Terpilih & Total Bobot) & Reset -->
                            <div class="flex flex-col items-end gap-2">
                                <div class="flex items-center gap-2">
                                    <button wire:click="resetSelectedQuestions" type="button" class="text-xs font-bold bg-white text-red-600 hover:bg-red-50 border border-red-200 px-3 py-2 rounded-lg shadow-sm transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Reset
                                    </button>
                                    <div class="text-sm font-medium text-gray-700 bg-white px-5 py-2 rounded-lg shadow-sm border {{ $total_points > 100 ? 'border-red-400 bg-red-50' : 'border-gray-200' }} whitespace-nowrap flex items-center space-x-4 transition-colors">
                                        <div>
                                            Terpilih: <span class="font-bold text-blue-600 text-base">{{ count($selected_questions) }}</span> soal
                                        </div>
                                        <div class="border-l border-gray-300 pl-4">
                                            Total Bobot: <span class="font-bold text-base {{ $total_points > 100 ? 'text-red-600' : 'text-emerald-600' }}">{{ $total_points }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($total_points > 100)
                                    <div class="text-xs font-bold text-red-500 mt-2 flex items-center animate-pulse">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        Peringatan: Total bobot melebihi 100!
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-white overflow-y-auto flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($bank_questions as $q)
                                <label class="relative flex p-4 cursor-pointer rounded-xl border {{ in_array($q->id, $selected_questions) ? 'bg-blue-50/50 border-blue-400 shadow-sm ring-1 ring-blue-400' : 'bg-white border-gray-200 hover:bg-gray-50' }} transition-all group">
                                    <div class="flex items-start h-5">
                                        <input type="checkbox" wire:model.live="selected_questions" value="{{ $q->id }}" class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mt-0.5">
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="flex justify-between items-start mb-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium {{ $q->type === 'multiple_choice' ? 'bg-indigo-100 text-indigo-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ $q->type === 'multiple_choice' ? 'PG' : 'Essay' }}
                                            </span>
                                            <span class="text-xs font-bold text-gray-500">{{ $q->points }} Poin</span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900 line-clamp-3 mb-2">{{ $q->text }}</p>
                                        <div class="flex items-center space-x-2 text-[10px] text-gray-500 uppercase font-semibold">
                                            <span>{{ $q->category ? $q->category->name : 'Tanpa Kategori' }}</span>
                                            <span>•</span>
                                            <span class="{{ $q->difficulty === 'easy' ? 'text-green-600' : ($q->difficulty === 'hard' ? 'text-red-600' : 'text-yellow-600') }}">{{ ucfirst($q->difficulty) }}</span>
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <div class="col-span-full py-8 text-center text-gray-500">
                                    Tidak ada soal di Bank Soal{{ $filter_category ? ' untuk kategori ini' : '' }}.
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 sm:flex sm:flex-row-reverse rounded-b-2xl shrink-0">
                        <button wire:click.prevent="syncQuestions()" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:-translate-y-0.5">
                            Simpan & Tautkan Soal
                        </button>
                        <button wire:click="closeQuestionModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Participant Assignment Modal -->
    @if($isAssignModalOpen)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-gray-100">
                    
                    <div class="bg-gradient-to-r from-blue-800 to-blue-600 px-6 py-4 border-b border-gray-100 flex justify-between items-center shrink-0">
                        <div class="text-white">
                            <h3 class="text-xl font-bold" id="modal-title">Penugasan Peserta</h3>
                            <p class="text-blue-100 text-sm mt-1">Ujian: {{ $assigning_exam_title }}</p>
                        </div>
                        <button wire:click="closeAssignModal()" class="text-blue-200 hover:text-white bg-blue-700 hover:bg-blue-800 rounded-full p-1.5 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="px-6 py-4">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Hanya peserta yang dicentang di bawah ini yang dapat mengakses ujian ini.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Wave Filter for Assignment -->
                        <div class="mb-4 flex items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <div class="text-sm font-semibold text-gray-700">Filter Peserta:</div>
                            <select wire:model.live="assigning_filter_wave" class="block w-full sm:w-64 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Semua Gelombang</option>
                                @foreach($waves as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="border rounded-lg border-gray-200 overflow-hidden shadow-inner bg-gray-50 max-h-[400px] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100 sticky top-0 z-10 shadow-sm">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12 text-center">
                                            Pilih
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Peserta
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Instansi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($all_participants as $participant)
                                        <tr class="hover:bg-blue-50 transition-colors {{ in_array((string)$participant->id, $selected_participants) ? 'bg-blue-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <input wire:model.live="selected_participants" type="checkbox" value="{{ $participant->id }}" class="focus:ring-blue-500 h-5 w-5 text-blue-600 border-gray-300 rounded shadow-sm cursor-pointer">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full border border-gray-200" src="{{ $participant->profile_photo_url }}" alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $participant->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $participant->participant_number ?? $participant->nik }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $participant->institution ?? '-' }}</div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                                <p class="text-lg font-medium">Tidak ada peserta terdaftar</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 sm:flex sm:flex-row-reverse rounded-b-2xl shrink-0">
                        <button wire:click.prevent="syncParticipants()" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:-translate-y-0.5" wire:loading.attr="disabled">
                            <svg wire:loading wire:target="syncParticipants" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Simpan Penugasan
                        </button>
                        <button wire:click="closeAssignModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all" wire:loading.attr="disabled">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
