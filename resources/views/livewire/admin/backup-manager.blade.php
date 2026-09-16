<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Backup & Restore Data
    </h2>
</x-slot>

<div class="w-full py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center text-sm text-gray-600 font-medium space-x-2 mb-6 px-2 sm:px-0">
        <span class="text-blue-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Admin
        </span>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 font-bold text-base">Backup & Restore</span>
    </div>

    <!-- Premium Header -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-600 rounded-2xl shadow-lg p-6 mb-8 text-white flex flex-col md:flex-row items-center justify-between transition-all duration-300">
        <div>
            <h2 class="text-3xl font-bold tracking-tight mb-1 flex items-center">
                <svg class="w-8 h-8 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Backup & Restore Data
            </h2>
            <p class="text-blue-100 opacity-90 text-sm">Amankan data ujian, soal, dan peserta, atau pulihkan dari cadangan sebelumnya.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-xl shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-xl shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Card Backup -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 p-8 flex flex-col justify-between">
            <div>
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Download Backup</h3>
                <p class="text-gray-500 mb-6">Buat cadangan seluruh data aplikasi termasuk database (soal, peserta, nilai) dan file media (foto soal, dll) ke dalam format ZIP.</p>
            </div>
            
            <button wire:click="downloadBackup" wire:loading.attr="disabled" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl shadow-md transition-all flex items-center justify-center disabled:opacity-50">
                <span wire:loading.remove wire:target="downloadBackup" class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Buat & Download Backup
                </span>
                <span wire:loading wire:target="downloadBackup" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Memproses Backup...
                </span>
            </button>
        </div>

        <!-- Card Restore -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 p-8 border-t-4 border-t-amber-500">
            <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Restore Data</h3>
            
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p class="text-sm text-red-800 font-medium">PERINGATAN: Melakukan restore akan <strong class="font-bold">menghapus dan menimpa</strong> seluruh data yang ada saat ini dengan data dari file backup. Tindakan ini tidak dapat dibatalkan!</p>
                </div>
            </div>

            <form wire:submit.prevent="restoreBackup">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Backup (.zip)</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                @if($backupFile)
                                    <p class="mb-2 text-sm font-bold text-blue-600">{{ $backupFile->getClientOriginalName() }}</p>
                                @else
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span></p>
                                    <p class="text-xs text-gray-500">File backup ZIP (Max 500MB)</p>
                                @endif
                            </div>
                            <input id="dropzone-file" wire:model="backupFile" type="file" class="hidden" accept=".zip" />
                        </label>
                    </div>
                    @error('backupFile') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                </div>

                <button type="submit" wire:loading.attr="disabled" {{ !$backupFile ? 'disabled' : '' }} wire:confirm="Anda yakin ingin me-restore data ini? Seluruh data saat ini akan dihapus permanen!" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 px-6 rounded-xl shadow-md transition-all flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="restoreBackup" class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Mulai Restore Data
                    </span>
                    <span wire:loading wire:target="restoreBackup" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Sedang Merestore...
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>
