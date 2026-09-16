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
        <div class="mt-4 md:mt-0">
            <button wire:click="create()" class="bg-white text-blue-700 hover:bg-blue-50 focus:ring-4 focus:ring-blue-300 font-bold py-2.5 px-5 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
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
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg">
                                            {{ strtoupper(substr($participant->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $participant->name }}</div>
                                        <div class="text-xs text-gray-500 font-medium">Bergabung {{ $participant->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $participant->participant_number ?: '-' }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $participant->nik ?: 'NIK Kosong' }}</div>
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
                            
                            <div>
                                <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="name" wire:model="name" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Masukkan nama peserta">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>
                            
                            <div>
                                <label for="nik" class="block text-gray-700 text-sm font-semibold mb-2">NIK / Username <span class="text-red-500">*</span></label>
                                <input type="text" id="nik" wire:model="nik" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm font-mono" placeholder="Masukkan NIK atau Username">
                                <p class="text-xs text-gray-500 mt-1">Gunakan NIK ini sebagai username saat login.</p>
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
</div>
