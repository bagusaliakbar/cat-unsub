<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa HTTPS di hosting agar Livewire tidak error (POST ter-redirect jadi GET)
        if (!app()->environment('local') || request()->header('x-forwarded-proto') === 'https' || (!str_contains(request()->getHost(), 'localhost') && !str_contains(request()->getHost(), '.test'))) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            
            // Konfigurasi endpoint Livewire agar tidak ter-redirect (memaksa HTTPS pada script dan update route)
            \Livewire\Livewire::setUpdateRoute(function ($handle) {
                return \Illuminate\Support\Facades\Route::post('/livewire/update', $handle)->middleware('web');
            });
        }
    }
}
