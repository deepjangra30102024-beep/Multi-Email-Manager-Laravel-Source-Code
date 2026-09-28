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
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::pluck('value', 'key');
                
                if (isset($settings['GOOGLE_CLIENT_ID'])) {
                    config(['services.google.client_id' => $settings['GOOGLE_CLIENT_ID']]);
                }
                if (isset($settings['GOOGLE_CLIENT_SECRET'])) {
                    config(['services.google.client_secret' => $settings['GOOGLE_CLIENT_SECRET']]);
                }
                if (isset($settings['GOOGLE_REDIRECT_URI'])) {
                    config(['services.google.redirect' => $settings['GOOGLE_REDIRECT_URI']]);
                }
            }
        } catch (\Exception $e) {
            // Ignore DB errors during boot (e.g. before migrations are run)
        }
    }
}
