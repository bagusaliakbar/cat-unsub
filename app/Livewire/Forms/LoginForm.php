<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        
        $user = Auth::user();
        if ($user && in_array($user->role, ['participant', 'peserta'])) {
            $currentSessionId = request()->session()->getId();
            $activeSession = \App\Models\ExamSession::where('user_id', $user->id)
                ->whereIn('status', ['started', 'in_progress'])
                ->whereNotNull('session_token')
                ->where('session_token', '!=', $currentSessionId)
                ->first();

            if ($activeSession) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'form.email' => 'Akun ini sedang aktif mengerjakan ujian di perangkat lain. Hubungi panitia untuk Buka Kunci Perangkat jika Anda berpindah perangkat.',
                ]);
            }
        }
        
        \App\Services\LogService::record('login', 'User berhasil login ke dalam sistem.');
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
