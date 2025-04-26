<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CarService;
use App\Models\User;
use App\Models\Setting;
use App\Notifications\AdminServiceAlert;
use App\Notifications\ServiceCompletionReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendServiceReminders extends Command
{
    protected $signature = 'autox:send-reminders';
    protected $description = 'Send reminders for services that have been in progress for more than 3 days';

    public function handle()
    {
        // Log start of reminder process
        Log::info('Starting service reminder process');

        // Get reminder days from settings or use default
        $reminderDays = config('autox.reminder_days', 3);
        $cutoffDate = Carbon::now()->subDays($reminderDays);
        
        // Get overdue services - using 'in-progress' with hyphen to match database
        $services = CarService::with('customer')
            ->where('status', 'in-progress')
            ->where('start_date', '<=', $cutoffDate)
            ->whereDoesntHave('notifications', function($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(1));
            })
            ->get();
        
        $customerCount = 0;
        $adminCount = 0;
        
        foreach ($services as $service) {
            try {
                // Send notification to customer
                if ($service->customer && $service->customer->email) {
                    $service->customer->notify(new ServiceCompletionReminder($service));
                    $customerCount++;
                    Log::info("Sent reminder to customer: {$service->customer->email} for service: {$service->order_id}");
                }
                
                // Send notification to admin for services older than 5 days
                if ($service->start_date && $service->start_date->diffInDays(now()) >= 5) {
                    $admins = User::where('is_admin', true)->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new AdminServiceAlert($service, 'overdue'));
                        $adminCount++;
                        Log::info("Sent overdue alert to admin: {$admin->email} for service: {$service->order_id}");
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error sending reminders for service {$service->id}: " . $e->getMessage());
            }
        }
        
        $this->info("Sent {$customerCount} customer service reminders.");
        $this->info("Sent {$adminCount} admin overdue alerts.");
        Log::info("Service reminder process completed. Sent {$customerCount} customer reminders and {$adminCount} admin alerts.");
    }
}