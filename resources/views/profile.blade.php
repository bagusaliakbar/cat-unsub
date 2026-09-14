<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Breadcrumb -->
            <div class="flex items-center text-sm text-gray-600 font-medium space-x-2 mb-2">
                <span class="text-blue-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Peserta
                </span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-900 font-bold text-base">Profil Pengguna</span>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Biodata Section (Takes up 2/3 width on large screens) -->
                <div class="xl:col-span-2">
                    <div class="p-6 sm:p-10 bg-white shadow-sm border border-gray-100 sm:rounded-2xl transition-all hover:shadow-md">
                        <livewire:profile.update-profile-information-form />
                    </div>
                </div>

                <!-- Right Column: Password & Delete Account -->
                <div class="xl:col-span-1 space-y-8">
                    <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 sm:rounded-2xl transition-all hover:shadow-md">
                        <livewire:profile.update-password-form />
                    </div>

                    <div class="p-6 sm:p-8 bg-red-50/30 shadow-sm border border-red-100 sm:rounded-2xl transition-all hover:shadow-md">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
