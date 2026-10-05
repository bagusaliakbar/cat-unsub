<?php

use App\Livewire\Forms\LoginForm;
use App\Services\TurnstileService;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;
    public string $turnstileToken = '';

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        // 1. Verify Cloudflare Turnstile if enabled
        if (config('services.turnstile.enabled', true) && !empty(config('services.turnstile.secret'))) {
            if (empty($this->turnstileToken) || !TurnstileService::verify($this->turnstileToken, request()->ip())) {
                $this->dispatch('reset-turnstile');
                $this->addError('turnstile', 'Verifikasi keamanan Turnstile gagal atau kedaluwarsa. Silakan coba lagi.');
                return;
            }
        }

        $this->validate();

        try {
            $this->form->authenticate();
        } catch (ValidationException $e) {
            $this->dispatch('reset-turnstile');
            throw $e;
        }

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-10 lg:mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Selamat Datang 👋</h2>
        <p class="text-gray-500 text-sm font-medium">Silakan masuk menggunakan akun Anda untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email / NIK / ID Peserta -->
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5">Alamat Email / NIK / ID Peserta</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <input wire:model="form.email" id="email" class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm sm:text-sm font-medium" type="text" name="email" required autofocus autocomplete="username" placeholder="Masukkan Email, NIK, atau ID Peserta" />
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password" class="block text-sm font-bold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors" href="{{ route('password.request') }}" wire:navigate>
                        Lupa password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input wire:model="form.password" id="password" class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm sm:text-sm font-medium" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember" class="flex items-center cursor-pointer group">
                <div class="relative flex items-center justify-center">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="peer sr-only" name="remember">
                    <div class="w-5 h-5 border-2 border-gray-300 rounded bg-white peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all"></div>
                    <svg class="absolute w-3 h-3 text-white hidden peer-checked:block pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-600 group-hover:text-gray-900 transition-colors">Ingat saya</span>
            </label>
        </div>

        <!-- Cloudflare Turnstile -->
        @if(config('services.turnstile.enabled', true))
            <div wire:ignore 
                 x-data="{
                     widgetId: null,
                     init() {
                         const renderWidget = () => {
                             if (typeof turnstile !== 'undefined' && this.$refs.turnstileContainer) {
                                 if (this.widgetId !== null) {
                                     try { turnstile.remove(this.widgetId); } catch(e) {}
                                 }
                                 this.widgetId = turnstile.render(this.$refs.turnstileContainer, {
                                     sitekey: '{{ config('services.turnstile.key') }}',
                                     theme: 'light',
                                     callback: (token) => {
                                         $wire.turnstileToken = token;
                                     },
                                     'expired-callback': () => {
                                         $wire.turnstileToken = '';
                                     },
                                     'error-callback': () => {
                                         $wire.turnstileToken = '';
                                     }
                                 });
                             }
                         };

                         if (typeof turnstile !== 'undefined') {
                             renderWidget();
                         } else {
                             const poll = setInterval(() => {
                                 if (typeof turnstile !== 'undefined') {
                                     clearInterval(poll);
                                     renderWidget();
                                 }
                             }, 100);
                         }

                         Livewire.on('reset-turnstile', () => {
                             if (typeof turnstile !== 'undefined' && this.widgetId !== null) {
                                 try { turnstile.reset(this.widgetId); } catch(e) {}
                             }
                             $wire.turnstileToken = '';
                         });
                     }
                 }" 
                 class="flex flex-col items-center justify-center my-3">
                <div x-ref="turnstileContainer"></div>
            </div>
            @error('turnstile')
                <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600 flex items-center font-medium">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        @endif

        <div class="pt-2">
            <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5 disabled:opacity-50">
                <span wire:loading.remove wire:target="login" class="flex items-center">
                    Masuk ke Sistem
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </span>
                <span wire:loading wire:target="login" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Memverifikasi...
                </span>
            </button>
        </div>
    </form>
    
    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
        <p class="text-sm text-gray-500 font-medium">Mengalami kendala saat login?</p>
        <a href="#" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors mt-1 inline-block">Hubungi Panitia Ujian</a>
    </div>
</div>
