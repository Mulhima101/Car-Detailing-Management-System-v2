<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // Add this import
use App\Models\Setting;

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
        // Load email templates from settings if available
        try {
            if (Schema::hasTable('settings')) {
                $settings = Setting::pluck('value', 'key')->toArray();
                
                // Set email template values in config
                foreach ($settings as $key => $value) {
                    if (strpos($key, 'email_') === 0) {
                        config(['autox.' . $key => $value]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail if database is not available yet
        }
    }
}