<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run reminders every day at 9:00 AM
        $schedule->command('autox:send-reminders')->dailyAt('09:00');
        
        // You could also add a log maintenance command to prevent logs from growing too large
        $schedule->command('log:clear')->weekly();
        
        // Queue worker check - ensure queue is being processed
        $schedule->command('queue:restart')->hourly();
        
        // Process the queue if using database driver
        $schedule->command('queue:work --stop-when-empty')->everyMinute()
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    
        require base_path('routes/console.php');
    }
}