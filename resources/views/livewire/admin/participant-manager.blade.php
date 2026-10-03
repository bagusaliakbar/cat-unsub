<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manajemen Peserta
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
        <span class="text-gray-900 font-bold text-base">Manajemen Peserta</span>
    </div>
    <!-- Premium Header -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-600 rounded-2xl shadow-lg p-6 mb-8 text-white flex flex-col md:flex-row items-center justify-between transition-all duration-300">
        <div>
            <h2 class="text-3xl font-bold tracking-tight mb-1 flex items-center">
                <svg class="w-8 h-8 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Manajemen Peserta
            </h2>
            <p class="text-blue-100 opacity-90 text-sm">Kelola data peserta, NIK, dan akses login ke sistem CAT.</p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-2.5">
            <button wire:click="export" wire:loading.attr="disabled" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2.5 px-4 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center text-sm disabled:opacity-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </button>
            <button wire:click="openImportModal" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2.5 px-4 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Excel
            </button>
            <a href="{{ route('admin.participants.print_all', ['wave_id' => $filter_wave, 'search' => $search]) }}" target="_blank" class="bg-indigo-500 hover:bg-indigo-400 text-white font-bold py-2.5 px-4 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Semua Kartu
            </a>
            <button wire:click="create()" class="bg-white text-blue-700 hover:bg-blue-50 focus:ring-4 focus:ring-blue-300 font-bold py-2.5 px-5 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Tambah Peserta
            </button>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 space-y-4 md:space-y-0">
        <div class="flex items-center space-x-4">
            <select wire:model.live="filter_wave" class="block w-full sm:w-48 rounded-full border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 px-4 bg-white">
                <option value="">Semua Gelombang</option>
                @foreach($waves as $w)
                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                @endforeach
            </select>

            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, nik, id..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-full leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out">
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-xl shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-xl shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID / NIK</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Instansi</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Gelombang</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($participants as $participant)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($participant->profile_photo_path)
                                            <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="{{ $participant->profile_photo_url }}" alt="{{ $participant->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg">
                                                {{ strtoupper(substr($participant->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $participant->name }}</div>
                                        <div class="text-xs text-gray-500 font-medium">Bergabung {{ $participant->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $participant->participant_number ?: '-' }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $participant->nik ? 'NIK: ' . $participant->nik : 'NIK: -' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $participant->institution ?: '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($participant->wave)
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $participant->wave->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $participant->email ?: '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.participants.print', $participant->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition flex items-center" title="Cetak Kartu">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        Cetak
                                    </a>
                                    <button wire:click="edit({{ $participant->id }})" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition flex items-center" title="Edit">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $participant->id }})" wire:confirm="Anda yakin ingin menghapus peserta ini?" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition flex items-center" title="Hapus">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="text-base font-medium">Tidak ada data peserta ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($participants->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                {{ $participants->links() }}
            </div>
        @endif
    </div>

    <!-- Modal for Create/Edit -->
    @if($isModalOpen)
        <div class="fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal Panel -->
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800" id="modal-title">
                            {{ $user_id ? 'Edit Peserta' : 'Tambah Peserta Baru' }}
                        </h3>
                        <button wire:click="closeModal()" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full p-1.5 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form>
                        <div class="px-6 py-6 bg-white space-y-5">
                            
                            <!-- Foto Upload -->
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">Pas Foto (Opsional)</label>
                                <div class="flex items-center space-x-4">
                                    <div class="shrink-0">
                                        @if ($photo)
                                            <img class="h-16 w-16 object-cover rounded-full border border-gray-200" src="{{ $photo->temporaryUrl() }}" alt="Preview">
                                        @elseif ($existing_photo_url)
                                            <img class="h-16 w-16 object-cover rounded-full border border-gray-200" src="{{ $existing_photo_url }}" alt="Current Photo">
                                        @else
                                            <div class="h-16 w-16 object-cover rounded-full border border-gray-200 bg-gray-50 flex items-center justify-center text-gray-400">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <label class="block">
                                        <span class="sr-only">Pilih pas foto</span>
                                        <input type="file" wire:model="photo" accept="image/*" class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-blue-50 file:text-blue-700
                                            hover:file:bg-blue-100 transition-colors cursor-pointer"
                                        />
                                    </label>
                                </div>
                                <div wire:loading wire:target="photo" class="text-sm text-blue-600 mt-2 font-medium">Mengunggah...</div>
                                @error('photo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="name" wire:model="name" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Masukkan nama peserta">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>
                            
                            <div>
                                <label for="nik" class="block text-gray-700 text-sm font-semibold mb-2">NIK / Username (Opsional)</label>
                                <input type="text" id="nik" wire:model="nik" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm font-mono" placeholder="Masukkan NIK atau Username (Opsional)">
                                <p class="text-xs text-gray-500 mt-1">Dapat dikosongkan jika peserta login menggunakan Email atau ID Peserta.</p>
                                @error('nik') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="participant_number" class="block text-gray-700 text-sm font-semibold mb-2">ID Peserta (Opsional)</label>
                                    <input type="text" id="participant_number" wire:model="participant_number" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Nomor urut / ID unik">
                                    @error('participant_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label for="institution" class="block text-gray-700 text-sm font-semibold mb-2">Instansi (Opsional)</label>
                                    <input type="text" id="institution" wire:model="institution" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Asal instansi / sekolah">
                                    @error('institution') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="birth_place" class="block text-gray-700 text-sm font-semibold mb-2">Tempat Lahir (Opsional)</label>
                                    <input type="text" id="birth_place" wire:model="birth_place" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Kota Kelahiran">
                                    @error('birth_place') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label for="birth_date" class="block text-gray-700 text-sm font-semibold mb-2">Tanggal Lahir (Opsional)</label>
                                    <input type="date" id="birth_date" wire:model="birth_date" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm">
                                    @error('birth_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div>
                                <label for="latest_education" class="block text-gray-700 text-sm font-semibold mb-2">Pendidikan Terakhir (Opsional)</label>
                                <select id="latest_education" wire:model="latest_education" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm">
                                    <option value="">Pilih Pendidikan...</option>
                                    <option value="SD/Sederajat">SD/Sederajat</option>
                                    <option value="SMP/Sederajat">SMP/Sederajat</option>
                                    <option value="SMA/SMK/Sederajat">SMA/SMK/Sederajat</option>
                                    <option value="D1">D1</option>
                                    <option value="D2">D2</option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                                @error('latest_education') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="address" class="block text-gray-700 text-sm font-semibold mb-2">Alamat (Opsional)</label>
                                <textarea id="address" wire:model="address" rows="2" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Alamat lengkap"></textarea>
                                @error('address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="wave_id" class="block text-gray-700 text-sm font-semibold mb-2">Gelombang Pelaksanaan (Opsional)</label>
                                <select id="wave_id" wire:model="wave_id" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm">
                                    <option value="">Pilih Gelombang...</option>
                                    @foreach($waves as $w)
                                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Pilih gelombang untuk mengelompokkan peserta saat pelaksanaan ujian.</p>
                                @error('wave_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="email" id="email" wire:model="email" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 pl-10 transition-colors shadow-sm" placeholder="contoh@email.com">
                                </div>
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password {{ $user_id ? '(Opsional)' : '*' }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <input type="password" id="password" wire:model="password" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 pl-10 transition-colors shadow-sm" placeholder="{{ $user_id ? 'Kosongkan jika tidak diubah' : 'Buat password' }}">
                                </div>
                                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                        </div>
                        
                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 sm:flex sm:flex-row-reverse rounded-b-2xl">
                            <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Data
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

    <!-- Modal Import Peserta -->
    @if($isImportModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeImportModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
                    
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-700 to-indigo-700 px-6 py-5 text-white flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/10 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold leading-6">Import Data Peserta</h3>
                                <p class="text-xs text-blue-100 mt-0.5">Unggah file Excel atau CSV untuk mendaftarkan peserta secara massal.</p>
                            </div>
                        </div>
                        <button wire:click="closeImportModal" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Step 1: Download Template Box -->
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">Belum punya format file?</h4>
                                    <p class="text-xs text-gray-600">Unduh contoh template Excel yang siap diisi.</p>
                                </div>
                            </div>
                            <button wire:click="downloadTemplate" type="button" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow transition transform hover:-translate-y-0.5 shrink-0">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Unduh Template
                            </button>
                        </div>

                        <!-- Step 2: Upload File Zone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih File Excel / CSV (.xlsx, .xls, .csv)</label>
                            <div class="border-2 border-dashed border-gray-300 hover:border-blue-500 rounded-2xl p-6 text-center transition-colors bg-gray-50/60 cursor-pointer relative">
                                <input type="file" wire:model="importFile" accept=".xlsx,.xls,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    </div>
                                    @if ($importFile)
                                        <div class="text-sm font-bold text-blue-700">{{ $importFile->getClientOriginalName() }}</div>
                                        <div class="text-xs text-gray-500">{{ number_format($importFile->getSize() / 1024, 1) }} KB</div>
                                    @else
                                        <div class="text-sm font-medium text-gray-700">Klik untuk memilih file atau seret file ke sini</div>
                                        <div class="text-xs text-gray-500">Maksimal 10 MB (.xlsx, .xls, .csv)</div>
                                    @endif
                                </div>
                            </div>
                            <div wire:loading wire:target="importFile" class="text-xs font-semibold text-blue-600 mt-2 flex items-center gap-1.5">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Mengunggah file...
                            </div>
                            @error('importFile') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span>@enderror
                        </div>

                        <!-- Panduan Kolom -->
                        <div class="bg-gray-50 rounded-xl p-3.5 border border-gray-200 text-xs text-gray-600 space-y-1.5">
                            <div class="font-bold text-gray-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Petunjuk Kolom Data:
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong class="text-gray-800">nama_lengkap</strong>: Kolom wajib diisi.</li>
                                <li><strong class="text-gray-800">nik</strong> & <strong class="text-gray-800">id_peserta</strong>: Bersifat opsional. Jika keduanya kosong, sistem otomatis membuatkan ID Peserta unik.</li>
                                <li><strong class="text-gray-800">password</strong>: Opsional. Jika dikosongkan, password otomatis di-set <span class="font-mono bg-white px-1 py-0.5 rounded border border-gray-200 text-gray-900 font-bold">123456</span>.</li>
                                <li><strong class="text-gray-800">gelombang</strong>: Nama gelombang (contoh: "Gelombang 1").</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex flex-row-reverse gap-3 rounded-b-2xl">
                        <button wire:click="import" wire:loading.attr="disabled" type="button" class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-sm transition disabled:opacity-50">
                            <span wire:loading wire:target="import" class="inline-flex items-center mr-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </span>
                            Mulai Import
                        </button>
                        <button wire:click="closeImportModal" type="button" class="w-full sm:w-auto inline-flex justify-center rounded-xl px-5 py-2.5 bg-white text-gray-700 font-medium text-sm border border-gray-300 hover:bg-gray-100 transition">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
