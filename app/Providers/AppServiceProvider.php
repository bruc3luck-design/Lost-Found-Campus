<?php

namespace App\Providers;

use App\Contracts\PortalAuthenticator;
use App\Services\FakePortalAuthenticator;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PortalAuthenticator::class, function ($app) {
            $driver = config('services.portal.driver', 'fake');

            if ($driver === 'fake') {
                if (app()->environment('production')) {
                    throw new RuntimeException(
                        'PORTAL_DRIVER=fake tidak diizinkan di production. Gunakan PORTAL_DRIVER=bridge.'
                    );
                }
                return new FakePortalAuthenticator();
            }

            if ($driver === 'bridge') {
                // Diimplementasikan pada Fase 6
                if (class_exists(\App\Services\BridgeAuthenticator::class)) {
                    return $app->make(\App\Services\BridgeAuthenticator::class);
                }
                throw new RuntimeException('BridgeAuthenticator belum diimplementasikan.');
            }

            throw new RuntimeException("PORTAL_DRIVER tidak dikenal: {$driver}");
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
