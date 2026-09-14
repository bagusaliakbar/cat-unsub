<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $participant_number = '';
    public string $phone = '';
    public string $gender = '';
    public string $birth_place = '';
    public string $birth_date = '';
    public string $address = '';
    public string $institution = '';
    public $photo;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->participant_number = $user->participant_number ?? '';
        $this->phone = $user->phone ?? '';
        $this->gender = $user->gender ?? '';
        $this->birth_place = $user->birth_place ?? '';
        $this->birth_date = $user->birth_date ? $user->birth_date->format('Y-m-d') : '';
        $this->address = $user->address ?? '';
        $this->institution = $user->institution ?? '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'participant_number' => ['nullable', 'string', 'max:50', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'institution' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'], // 2MB Max
        ]);

        if (isset($validated['photo']) && $this->photo) {
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }
            $validated['profile_photo_path'] = $this->photo->store('profile-photos', 'public');
        }
        unset($validated['photo']);

        $user->fill($validated);

        if ($user->isDirty('email') && $user->email !== null) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="bg-white">
    <header class="mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-900">
            {{ __('Biodata Diri') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui informasi profil dan biodata lengkap Anda.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6">
        <!-- Profile Photo Row -->
        <div class="mb-8 flex items-center space-x-6">
            <div class="shrink-0">
                @if ($photo)
                    <img class="h-24 w-24 object-cover rounded-full border-4 border-white shadow-md" src="{{ $photo->temporaryUrl() }}" alt="Preview Foto">
                @else
                    <img class="h-24 w-24 object-cover rounded-full border-4 border-white shadow-md" src="{{ auth()->user()->profile_photo_url }}" alt="Foto Profil Saat Ini">
                @endif
            </div>
            <div>
                <label class="block">
                    <span class="sr-only">Pilih foto profil</span>
                    <input type="file" wire:model="photo" accept="image/*" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100 transition-colors
                    "/>
                </label>
                <div wire:loading wire:target="photo" class="text-sm text-blue-600 mt-2">Mengunggah...</div>
                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF. Maksimal: 2MB.</p>
            </div>
        </div>

        <!-- NIK / Name Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full bg-gray-50 border-gray-200" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email (Opsional)')" />
                <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full bg-gray-50 border-gray-200" autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail() && auth()->user()->email)
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Participant Number & Institution Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <x-input-label for="participant_number" :value="__('Nomor Ujian / Peserta (Opsional)')" />
                <x-text-input wire:model="participant_number" id="participant_number" name="participant_number" type="text" class="mt-1 block w-full bg-gray-50 border-gray-200" placeholder="Misal: 2026-001-002" />
                <x-input-error class="mt-2" :messages="$errors->get('participant_number')" />
            </div>

            <div>
                <x-input-label for="institution" :value="__('Asal Sekolah / Instansi')" />
                <x-text-input wire:model="institution" id="institution" name="institution" type="text" class="mt-1 block w-full bg-gray-50 border-gray-200" />
                <x-input-error class="mt-2" :messages="$errors->get('institution')" />
            </div>
        </div>

        <!-- Phone & Gender -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <x-input-label for="phone" :value="__('No. Handphone / WhatsApp')" />
                <x-text-input wire:model="phone" id="phone" name="phone" type="text" class="mt-1 block w-full bg-gray-50 border-gray-200" placeholder="081234567890" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="gender" :value="__('Jenis Kelamin')" />
                <select wire:model="gender" id="gender" name="gender" class="mt-1 block w-full border-gray-200 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>
        </div>

        <!-- Birth Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <x-input-label for="birth_place" :value="__('Tempat Lahir')" />
                <x-text-input wire:model="birth_place" id="birth_place" name="birth_place" type="text" class="mt-1 block w-full bg-gray-50 border-gray-200" />
                <x-input-error class="mt-2" :messages="$errors->get('birth_place')" />
            </div>

            <div>
                <x-input-label for="birth_date" :value="__('Tanggal Lahir')" />
                <x-text-input wire:model="birth_date" id="birth_date" name="birth_date" type="date" class="mt-1 block w-full bg-gray-50 border-gray-200" />
                <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
            </div>
        </div>

        <!-- Address -->
        <div class="grid grid-cols-1 gap-6 mb-6">
            <div>
                <x-input-label for="address" :value="__('Alamat Lengkap')" />
                <textarea wire:model="address" id="address" name="address" rows="3" class="mt-1 block w-full border-gray-200 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-8 pt-4 border-t border-gray-100">
            <x-action-message class="me-3 font-medium text-green-600" on="profile-updated">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ __('Biodata Berhasil Disimpan.') }}
                </div>
            </x-action-message>

            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                {{ __('Simpan Biodata') }}
            </button>
        </div>
    </form>
</section>
