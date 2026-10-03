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
        <div class="mt-4 md:mt-0 flex flex-wrap items-center gap-3 sm:gap-3.5">
            <button wire:click="export" wire:loading.attr="disabled" class="bg-blue-500 hover:bg-blue-400 text-white font-semibold py-2.5 px-4 rounded-full shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center text-sm disabled:opacity-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </button>
            <button wire:click="openImportModal" class="bg-blue-500 hover:bg-blue-400 text-white font-semibold py-2.5 px-4 rounded-full shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Excel
            </button>
            <a href="{{ route('admin.participants.print_all', ['wave_id' => $filter_wave, 'search' => $search]) }}" target="_blank" class="bg-blue-500 hover:bg-blue-400 text-white font-semibold py-2.5 px-4 rounded-full shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Semua Kartu
            </a>
            <button wire:click="create()" class="bg-white text-blue-700 hover:bg-blue-50 focus:ring-4 focus:ring-blue-300 font-bold py-2.5 px-5 rounded-full shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center text-sm">
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
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Instansi & Pendidikan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Alamat</th>
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
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-base">
                                                {{ strtoupper(substr($participant->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3.5">
                                        <div class="text-sm font-bold text-gray-900 tracking-tight">{{ $participant->name }}</div>
                                        @if($participant->birth_place || $participant->birth_date)
                                            <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                                <svg class="w-3.5 h-3.5 mr-1 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span>
                                                    {{ $participant->birth_place ? $participant->birth_place . ($participant->birth_date ? ', ' : '') : '' }}
                                                    {{ $participant->birth_date ? $participant->birth_date->format('d M Y') : '' }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="text-xs text-gray-400 mt-0.5">Bergabung {{ $participant->created_at->format('d M Y') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 font-mono">{{ $participant->participant_number ?: '-' }}</div>
                                <div class="text-xs text-gray-500 font-mono mt-0.5">
                                    {{ $participant->nik ? 'NIK: ' . $participant->nik : 'NIK: -' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $participant->institution ?: '-' }}</div>
                                @if($participant->latest_education)
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $participant->latest_education }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($participant->address)
                                    <div class="text-xs text-gray-600 max-w-[200px] line-clamp-2 leading-relaxed" title="{{ $participant->address }}">
                                        {{ $participant->address }}
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
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
                                <div class="space-y-1">
                                    @if($participant->email)
                                        <div class="text-xs text-gray-700 flex items-center font-medium">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            <span>{{ $participant->email }}</span>
                                        </div>
                                    @endif
                                    @if($participant->phone)
                                        <div class="text-xs text-gray-600 flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            <span>{{ $participant->phone }}</span>
                                        </div>
                                    @endif
                                    @if(!$participant->email && !$participant->phone)
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </div>
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
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
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
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center border shadow-xs" style="background-color: #eff6ff !important; color: #2563eb !important; border-color: #dbeafe !important;">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 leading-tight" id="modal-title">Import Data Peserta</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Unggah file Excel atau CSV untuk mendaftarkan peserta secara massal.</p>
                            </div>
                        </div>
                        <button wire:click="closeImportModal" type="button" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full p-1.5 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- Step 1: Download Template Box -->
                        <div class="rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs border" style="background-color: #eff6ff !important; border-color: #bfdbfe !important;">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm" style="background-color: #2563eb !important; color: #ffffff !important;">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">Belum punya format file?</h4>
                                    <p class="text-xs text-gray-600 mt-0.5">Unduh contoh template Excel yang siap diisi.</p>
                                </div>
                            </div>
                            <button wire:click="downloadTemplate" type="button" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold rounded-xl shadow-sm transition transform hover:-translate-y-0.5 shrink-0" style="background-color: #2563eb !important; color: #ffffff !important;">
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
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-xs" style="background-color: #dbeafe !important; color: #2563eb !important;">
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

                        <!-- Panduan Kolom Data -->
                        <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-200/80 space-y-3">
                            <div class="flex items-center space-x-2 text-xs font-bold text-gray-800">
                                <div class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span>Petunjuk Format Kolom Excel:</span>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <!-- nama_lengkap -->
                                <div class="bg-white p-2.5 rounded-xl border border-gray-200 shadow-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <code class="text-xs font-bold font-mono text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">nama_lengkap</code>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-red-100 text-red-700">Wajib</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Nama lengkap calon peserta ujian.</p>
                                </div>

                                <!-- nik & id_peserta -->
                                <div class="bg-white p-2.5 rounded-xl border border-gray-200 shadow-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <code class="text-xs font-bold font-mono text-gray-800 bg-gray-100 px-1.5 py-0.5 rounded">nik & id_peserta</code>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-gray-100 text-gray-600">Opsional</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Jika kosong, ID unik dibuat otomatis.</p>
                                </div>

                                <!-- password -->
                                <div class="bg-white p-2.5 rounded-xl border border-gray-200 shadow-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <code class="text-xs font-bold font-mono text-gray-800 bg-gray-100 px-1.5 py-0.5 rounded">password</code>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-gray-100 text-gray-600">Opsional</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Default: <span class="font-mono font-bold text-gray-700 bg-gray-100 px-1 rounded">123456</span> jika kosong.</p>
                                </div>

                                <!-- gelombang -->
                                <div class="bg-white p-2.5 rounded-xl border border-gray-200 shadow-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <code class="text-xs font-bold font-mono text-gray-800 bg-gray-100 px-1.5 py-0.5 rounded">gelombang</code>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-gray-100 text-gray-600">Opsional</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Contoh: "Gelombang 1" atau nama sesi.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 rounded-b-2xl">
                        <button wire:click="closeImportModal" type="button" class="w-full sm:w-auto inline-flex justify-center rounded-xl px-5 py-2.5 bg-white text-gray-700 font-medium text-sm border border-gray-300 hover:bg-gray-100 transition shadow-xs">
                            Batal
                        </button>
                        <button wire:click="import" wire:loading.attr="disabled" type="button" class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl px-5 py-2.5 text-white font-bold text-sm shadow-sm transition transform hover:-translate-y-0.5 disabled:opacity-50" style="background-color: #2563eb !important; color: #ffffff !important;">
                            <span wire:loading wire:target="import" class="inline-flex items-center mr-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </span>
                            Mulai Import
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
