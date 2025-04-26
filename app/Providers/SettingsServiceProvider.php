<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use PDOException;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load settings from database into config
        try {
            if(Schema::hasTable('settings')) {
                $settings = Setting::pluck('value', 'key')->toArray();
                
                foreach ($settings as $key => $value) {
                    config(['autox.' . $key => $value]);
                }
            }
        } catch (PDOException $e) {
            // Database might not be available yet (during migrations)
        } catch (\Exception $e) {
            // Handle other exceptions
        }
    }
}