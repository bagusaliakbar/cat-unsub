<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Bank Soal
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
        <span class="text-gray-900 font-bold text-base">Bank Soal</span>
    </div>
    <!-- Premium Header -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-600 rounded-2xl shadow-lg p-6 mb-8 text-white flex flex-col md:flex-row items-center justify-between transition-all duration-300">
        <div>
            <h2 class="text-3xl font-bold tracking-tight mb-1 flex items-center">
                <svg class="w-8 h-8 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Bank Soal
            </h2>
            <p class="text-blue-100 opacity-90 text-sm">Kelola seluruh soal Anda berdasarkan kategori dan tingkat kesulitan.</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-2">
            <button wire:click="export" class="bg-green-600 hover:bg-green-700 text-white focus:ring-4 focus:ring-green-400 font-medium py-2.5 px-4 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center border border-green-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export
            </button>
            <button wire:click="openImportModal" class="bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-4 focus:ring-yellow-400 font-medium py-2.5 px-4 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center border border-yellow-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import
            </button>
            <button wire:click="openCategoryModal()" class="bg-blue-700 hover:bg-blue-800 text-white focus:ring-4 focus:ring-blue-400 font-medium py-2.5 px-4 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center border border-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Kategori
            </button>
            <button wire:click="create()" class="bg-white text-blue-700 hover:bg-blue-50 focus:ring-4 focus:ring-blue-300 font-bold py-2.5 px-5 rounded-full shadow-md transition transform hover:-translate-y-0.5 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Buat Soal
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

    <!-- Filters & View Toggle -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-wrap gap-4 items-end justify-between">
        <div class="flex flex-wrap gap-4 w-full md:w-auto">
            <div class="w-full md:w-48">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Filter Kategori</label>
                <select wire:model.live="filter_category" class="w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-48">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Filter Kesulitan</label>
                <select wire:model.live="filter_difficulty" class="w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    <option value="">Semua Kesulitan</option>
                    <option value="easy">Mudah</option>
                    <option value="medium">Sedang</option>
                    <option value="hard">Sulit</option>
                </select>
            </div>
        </div>
        
        <div class="flex items-center space-x-1 bg-gray-100 p-1 rounded-lg">
            <button wire:click="$set('viewMode', 'grid')" class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors flex items-center {{ $viewMode === 'grid' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Card
            </button>
            <button wire:click="$set('viewMode', 'list')" class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors flex items-center {{ $viewMode === 'list' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                List
            </button>
        </div>
    </div>

    @if($viewMode === 'grid')
        <!-- Cards Layout for Questions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($questions as $q)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col group">
                    <!-- Card Header -->
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-start">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $q->type === 'multiple_choice' ? 'bg-indigo-100 text-indigo-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $q->type === 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}
                            </span>
                            <div class="mt-1 flex items-center space-x-2 text-xs text-gray-500">
                                <span class="font-medium text-gray-700">{{ $q->category ? $q->category->name : 'Tanpa Kategori' }}</span>
                                <span>•</span>
                                <span class="{{ $q->difficulty === 'easy' ? 'text-green-600' : ($q->difficulty === 'hard' ? 'text-red-600' : 'text-yellow-600') }}">
                                    {{ ucfirst($q->difficulty) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-xs font-bold text-gray-400">Poin</span>
                            <span class="text-lg font-bold text-blue-600">{{ $q->points }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-grow">
                        <p class="text-gray-800 font-medium text-sm line-clamp-3 mb-4">
                            {{ Str::limit($q->text, 120) }}
                        </p>

                        @if($q->type === 'multiple_choice')
                            <div class="space-y-2 mt-auto">
                                @foreach($q->options as $opt)
                                    <div class="flex items-center text-xs p-2 rounded-lg border {{ $opt->is_correct ? 'bg-emerald-50 border-emerald-200 text-emerald-900 font-semibold' : 'bg-gray-50 border-transparent text-gray-600' }}">
                                        @if($opt->is_correct)
                                            <svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <div class="w-4 h-4 mr-2 flex-shrink-0"></div>
                                        @endif
                                        <span class="truncate">{{ $opt->text }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer (Actions) -->
                    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50 flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <button wire:click="edit({{ $q->id }})" class="p-2 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button wire:click="delete({{ $q->id }})" wire:confirm="Yakin ingin menghapus soal ini dari Bank Soal?" class="p-2 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="bg-gray-50 text-gray-400 p-4 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada soal</h3>
                        <p class="text-sm text-gray-500 mb-4">Mulai bangun Bank Soal Anda sekarang.</p>
                        <button wire:click="create()" class="text-blue-600 font-bold hover:underline">Buat Soal Pertama &rarr;</button>
                    </div>
                </div>
            @endforelse
        </div>
    @else
        <!-- List Layout for Questions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                            <th class="p-4 pl-6">Kategori</th>
                            <th class="p-4">Pertanyaan</th>
                            <th class="p-4">Tipe & Kesulitan</th>
                            <th class="p-4 text-center">Poin</th>
                            <th class="p-4 text-right pr-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($questions as $q)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4 pl-6 text-gray-700 font-medium">
                                    {{ $q->category ? $q->category->name : 'Tanpa Kategori' }}
                                </td>
                                <td class="p-4">
                                    <div class="text-gray-900 line-clamp-2 max-w-md" title="{{ $q->text }}">{{ Str::limit($q->text, 80) }}</div>
                                    @if($q->type === 'multiple_choice' && $q->options->count() > 0)
                                        <div class="mt-1 flex space-x-2 text-xs text-gray-400">
                                            @foreach($q->options as $opt)
                                                @if($opt->is_correct)
                                                    <span class="text-emerald-600 font-medium">✓ {{ Str::limit($opt->text, 20) }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-col space-y-1">
                                        <span class="inline-flex items-center w-fit px-2 py-0.5 rounded-full text-[10px] font-medium {{ $q->type === 'multiple_choice' ? 'bg-indigo-100 text-indigo-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $q->type === 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}
                                        </span>
                                        <span class="text-xs {{ $q->difficulty === 'easy' ? 'text-green-600' : ($q->difficulty === 'hard' ? 'text-red-600' : 'text-yellow-600') }}">
                                            {{ ucfirst($q->difficulty) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="font-bold text-gray-700">{{ $q->points }}</span>
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button wire:click="edit({{ $q->id }})" class="p-1.5 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-md transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button wire:click="delete({{ $q->id }})" wire:confirm="Yakin ingin menghapus soal ini dari Bank Soal?" class="p-1.5 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-md transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        <p class="font-medium">Belum ada soal</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($questions->hasPages())
        <div class="mt-6">
            {{ $questions->links() }}
        </div>
    @endif

    <!-- Category Modal -->
    @if($isCategoryModalOpen)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800" id="modal-title">Tambah Kategori Ujian</h3>
                        <button wire:click="closeCategoryModal()" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full p-1.5 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="px-6 py-6 bg-white space-y-5">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="category_name" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Contoh: Matematika Dasar">
                            @error('category_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi (Opsional)</label>
                            <textarea wire:model="category_description" rows="3" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 sm:flex sm:flex-row-reverse rounded-b-2xl">
                        <button wire:click.prevent="storeCategory()" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:-translate-y-0.5">
                            Simpan Kategori
                        </button>
                        <button wire:click="closeCategoryModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Question Modal Form -->
    @if($isModalOpen)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800" id="modal-title">
                            {{ $question_id ? 'Edit Soal' : 'Buat Soal Baru' }}
                        </h3>
                        <button wire:click="closeModal()" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full p-1.5 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form>
                        <div class="px-6 py-6 bg-white space-y-5 max-h-[70vh] overflow-y-auto">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kategori <span class="text-red-500">*</span></label>
                                    <select wire:model="category_id" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 text-sm font-semibold mb-2">Tingkat Kesulitan</label>
                                    <select wire:model="difficulty" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm">
                                        <option value="easy">Mudah</option>
                                        <option value="medium">Sedang</option>
                                        <option value="hard">Sulit</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">Teks Soal <span class="text-red-500">*</span></label>
                                <textarea wire:model="text" rows="4" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm" placeholder="Tuliskan pertanyaan disini..."></textarea>
                                @error('text') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-semibold mb-2">Tipe Soal</label>
                                    <select wire:model.live="type" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm">
                                        <option value="multiple_choice">Pilihan Ganda</option>
                                        <option value="essay">Essay</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-semibold mb-2">Poin</label>
                                    <input type="number" wire:model="points" min="1" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm">
                                    @error('points') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            @if($type === 'multiple_choice')
                                <div class="mt-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <label class="block text-gray-700 text-sm font-semibold">Opsi Jawaban</label>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md">Centang untuk Kunci Jawaban</span>
                                    </div>
                                    @error('options') <div class="text-red-500 text-xs mb-2 bg-red-50 p-2 rounded">{{ $message }}</div>@enderror
                                    
                                    <div class="space-y-3">
                                        @foreach($options as $index => $option)
                                            <div class="flex items-center space-x-3 group">
                                                <div class="relative flex items-center justify-center">
                                                    <input type="checkbox" wire:model="options.{{ $index }}.is_correct" class="w-5 h-5 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 focus:ring-2 cursor-pointer transition-all">
                                                </div>
                                                <div class="flex-grow">
                                                    <input type="text" wire:model="options.{{ $index }}.text" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-colors shadow-sm {{ $options[$index]['is_correct'] ? 'ring-1 ring-emerald-500 bg-emerald-50/30' : '' }}" placeholder="Opsi {{ chr(65 + $index) }}">
                                                </div>
                                            </div>
                                            @error('options.'.$index.'.text') <span class="text-red-500 text-xs ml-8">{{ $message }}</span>@enderror
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-400 mt-3"><svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Biarkan kosong jika tidak digunakan (misal hanya butuh 4 opsi).</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 sm:flex sm:flex-row-reverse rounded-b-2xl">
                            <button wire:click.prevent="store()" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Soal
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

    <!-- Import Modal -->
    @if($isImportModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-50 transition-opacity">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Import Soal (Excel)
                    </h3>
                    <button wire:click="closeImportModal" class="text-gray-400 hover:text-gray-500 transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-4">
                            Unggah file Excel (.xlsx, .xls) yang berisi soal-soal ujian. Anda dapat mengunduh format kolom yang benar dengan melakukan <strong>Export Excel</strong> terlebih dahulu.
                        </p>
                        
                        <label class="block text-sm font-semibold text-gray-700 mb-2">File Excel</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-500 transition-colors group relative cursor-pointer" onclick="document.getElementById('file-upload').click()">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <span class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Unggah sebuah file</span>
                                        <input id="file-upload" wire:model="importFile" type="file" class="sr-only" accept=".xlsx, .xls, .csv">
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">XLSX, XLS hingga 10MB</p>
                            </div>
                        </div>
                        
                        <!-- Show selected file name -->
                        <div wire:loading wire:target="importFile" class="mt-2 text-sm text-blue-600 font-medium">
                            Mengunggah file...
                        </div>
                        
                        @if($importFile)
                            <div class="mt-3 p-3 bg-green-50 border border-green-100 rounded-lg flex items-center text-sm text-green-700">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $importFile->getClientOriginalName() }} siap diimpor.
                            </div>
                        @endif
                        
                        @error('importFile') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <button wire:click="closeImportModal" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200">
                            Batal
                        </button>
                        <button wire:click="import" wire:loading.attr="disabled" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 flex items-center">
                            <span wire:loading.remove wire:target="import">Import Data</span>
                            <span wire:loading wire:target="import" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
