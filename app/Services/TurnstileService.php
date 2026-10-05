<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileService
{
    /**
     * Verify Cloudflare Turnstile response token.
     */
    public static function verify(?string $token, ?string $ip = null): bool
    {
        if (!config('services.turnstile.enabled', true)) {
            return true;
        }

        $secret = config('services.turnstile.secret');
        if (empty($secret)) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::asForm()->timeout(6)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $ip ?? request()->ip(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['success']) && $data['success'] === true;
            }

            Log::warning('Cloudflare Turnstile verification failed', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('Cloudflare Turnstile verification exception: ' . $e->getMessage());
            return false;
        }
    }
}
