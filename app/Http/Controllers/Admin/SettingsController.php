<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        
        // Direct update using query builder to avoid model method issues
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'updated_at' => now()
            ]);
        
        return redirect()->route('admin.settings')->with('success', 'Profile updated successfully.');
    }
    
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Direct update using query builder
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($validated['password']),
                'updated_at' => now()
            ]);
        
        return redirect()->route('admin.settings')->with('success', 'Password changed successfully.');
    }
    
    public function updateNotifications(Request $request)
    {
        $settings = [
            'notification_status_updates' => $request->has('notification_status_updates') ? '1' : '0',
            'notification_completion_reminders' => $request->has('notification_completion_reminders') ? '1' : '0',
            'notification_marketing_emails' => $request->has('notification_marketing_emails') ? '1' : '0',
        ];
        
        foreach ($settings as $key => $value) {
            // Check if setting exists
            $exists = DB::table('settings')->where('key', $key)->exists();
            
            if ($exists) {
                // Update
                DB::table('settings')
                    ->where('key', $key)
                    ->update(['value' => $value, 'updated_at' => now()]);
            } else {
                // Insert
                DB::table('settings')->insert([
                    'key' => $key,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        return redirect()->route('admin.settings')->with('success', 'Notification settings updated successfully.');
    }
    
    public function updateSystem(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'required|string|max:20',
            'reminder_days' => 'required|integer|min:1|max:14',
        ]);
        
        foreach ($validated as $key => $value) {
            // Check if setting exists
            $exists = DB::table('settings')->where('key', $key)->exists();
            
            if ($exists) {
                // Update
                DB::table('settings')
                    ->where('key', $key)
                    ->update(['value' => $value, 'updated_at' => now()]);
            } else {
                // Insert
                DB::table('settings')->insert([
                    'key' => $key,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        // Update config values at runtime
        config(['autox.reminder_days' => $validated['reminder_days']]);
        
        return redirect()->route('admin.settings')->with('success', 'System settings updated successfully.');
    }
    
    public function updateEmailTemplates(Request $request)
    {
        $templateType = $request->input('template_type');
        $subject = $request->input('subject');
        $template = $request->input('template');
        
        if ($templateType) {
            // Subject setting - check and update/insert
            $subjectKey = 'email_' . $templateType . '_subject';
            $exists = DB::table('settings')->where('key', $subjectKey)->exists();
            
            if ($exists) {
                DB::table('settings')
                    ->where('key', $subjectKey)
                    ->update(['value' => $subject, 'updated_at' => now()]);
            } else {
                DB::table('settings')->insert([
                    'key' => $subjectKey,
                    'value' => $subject,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Template setting - check and update/insert
            $templateKey = 'email_' . $templateType . '_template';
            $exists = DB::table('settings')->where('key', $templateKey)->exists();
            
            if ($exists) {
                DB::table('settings')
                    ->where('key', $templateKey)
                    ->update(['value' => $template, 'updated_at' => now()]);
            } else {
                DB::table('settings')->insert([
                    'key' => $templateKey,
                    'value' => $template,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        return redirect()->route('admin.settings')->with('success', 'Email template updated successfully.');
    }

    public function processQueue(Request $request)
    {
        try {
            // Set a time limit to prevent timeout
            set_time_limit(60);
            
            // Process the queue jobs
            $exitCode = Artisan::call('queue:work', [
                '--stop-when-empty' => true,
                '--queue' => 'default',
                '--timeout' => 55,
            ]);
            
            // Get the output
            $output = Artisan::output();
            
            // Log the result
            Log::info('Queue processed via settings: ' . $output);
            
            if ($exitCode === 0) {
                return redirect()->route('admin.settings')->with('success', 'Emails have been processed!');
            } else {
                return redirect()->route('admin.settings')->with('error', 'Error processing emails. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Error processing queue: ' . $e->getMessage());
            return redirect()->route('admin.settings')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function isQueueWorkerRunning()
    {
        // On shared hosting, we can check if there's a "queue-worker.pid" file
        $pidFile = storage_path('app/queue-worker.pid');
        
        if (file_exists($pidFile)) {
            $pid = file_get_contents($pidFile);
            
            // Check if the process is actually running
            // This won't work on all hosts, but it's worth trying
            try {
                if (function_exists('exec')) {
                    exec("ps -p $pid", $output, $result);
                    return $result === 0; // If result is 0, process is running
                }
                
                // Alternative check - consider worker active if pid file was modified recently (last 5 minutes)
                return (time() - filemtime($pidFile)) < 300;
            } catch (\Exception $e) {
                // If we can't check the process, assume it's not running
                return false;
            }
        }
        
        return false;
    }

    public function startQueueWorker(Request $request)
    {        
        try {
            \App\Jobs\PingQueueWorker::dispatch()->delay(now()->addMinutes(2));

            // Create a pid file to indicate worker is running
            $pidFile = storage_path('app/queue-worker.pid');
            
            // Start the queue worker as a background process
            if (function_exists('shell_exec')) {
                // Try to start the queue worker and run in background
                $command = 'nohup php ' . base_path('artisan') . ' queue:work --daemon > /dev/null 2>&1 & echo $!';
                $pid = shell_exec($command);
                
                // Save the PID to the file
                file_put_contents($pidFile, trim($pid));
                
                Log::info('Queue worker started with PID: ' . $pid);
                return redirect()->route('admin.settings')->with('success', 'Queue worker started successfully!');
            } else {
                // If shell_exec is disabled, we'll create a timestamp file to simulate worker running
                file_put_contents($pidFile, time());
                
                // Start a single job to show some progress
                Artisan::call('queue:work', ['--once' => true]);
                
                return redirect()->route('admin.settings')
                    ->with('warning', 'Limited queue worker functionality due to server restrictions. Processed one job.');
            }
        } catch (\Exception $e) {
            Log::error('Error starting queue worker: ' . $e->getMessage());
            return redirect()->route('admin.settings')->with('error', 'Error starting queue worker: ' . $e->getMessage());
        }
    }

    public function stopQueueWorker(Request $request)
    {
        try {
            $pidFile = storage_path('app/queue-worker.pid');
            
            if (file_exists($pidFile)) {
                $pid = file_get_contents($pidFile);
                
                // Try to kill the process
                if (function_exists('shell_exec') && is_numeric(trim($pid))) {
                    shell_exec('kill ' . trim($pid));
                    Log::info('Queue worker stopped. PID: ' . $pid);
                }
                
                // Remove the PID file
                unlink($pidFile);
                
                return redirect()->route('admin.settings')->with('success', 'Queue worker stopped successfully!');
            }
            
            return redirect()->route('admin.settings')->with('info', 'Queue worker is not running.');
        } catch (\Exception $e) {
            Log::error('Error stopping queue worker: ' . $e->getMessage());
            return redirect()->route('admin.settings')->with('error', 'Error stopping queue worker: ' . $e->getMessage());
        }
    }
}