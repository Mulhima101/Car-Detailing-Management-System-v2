<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PingQueueWorker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Update the timestamp of the PID file
        $pidFile = storage_path('app/queue-worker.pid');
        if (file_exists($pidFile)) {
            touch($pidFile);
        }
        
        // Dispatch another ping job in 2 minutes
        self::dispatch()->delay(now()->addMinutes(2));
    }
}