<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\CarService;
use App\Models\User;
use App\Notifications\AdminServiceAlert;
use App\Notifications\ServiceCompletionReminder;
use App\Notifications\ServiceStatusUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomServiceEmail;

class TestAllEmails extends Command
{
    protected $signature = 'autox:test-all-emails {email=redpostworks@gmail.com}';
    protected $description = 'Test all email functionality in one go';

    public function handle()
    {
        $email = $this->argument('email');
        $this->info('Starting comprehensive email tests to ' . $email);

        // 1. Test basic email
        $this->testBasicEmail($email);
        
        // 2. Set up a test customer and service
        $customer = $this->setupCustomer($email);
        $service = $this->setupService($customer->id);
        
        // 3. Set up admin
        $admin = $this->setupAdmin($email);
        
        // 4. Test customer emails
        $this->testCustomerEmails($customer, $service);
        
        // 5. Test admin emails
        $this->testAdminEmails($admin, $service);
        
        $this->info("\nAll tests completed. Please check your inbox at: " . $email);
    }
    
    private function testBasicEmail($email)
    {
        $this->info("\n1. Testing basic email capability...");
        try {
            Mail::raw('Basic AutoX email test at ' . now(), function ($message) use ($email) {
                $message->to($email)
                    ->subject('AutoX Basic Email Test');
            });
            $this->info('✓ Basic email sent successfully');
        } catch (\Exception $e) {
            $this->error('✗ Basic email failed: ' . $e->getMessage());
        }
    }
    
    private function setupCustomer($email)
    {
        $this->info("\n2. Setting up test customer...");
        $customer = Customer::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Test Customer',
                'phone' => '555-TEST-CUST',
                'address' => '123 Test Customer St'
            ]
        );
        $this->info('✓ Customer ready: ' . $customer->name . ' (ID: ' . $customer->id . ')');
        return $customer;
    }
    
    private function setupService($customerId)
    {
        $this->info("\n3. Setting up test service...");
        $service = CarService::create([
            'customer_id' => $customerId,
            'order_id' => 'AX' . date('Ymd') . rand(100, 999),
            'car_brand' => 'Test Brand',
            'car_model' => 'Email Test Model',
            'license_plate' => 'TEST123',
            'services' => ['Email Testing Service'],
            'status' => 'pending'
        ]);
        $this->info('✓ Service created: ' . $service->order_id . ' (ID: ' . $service->id . ')');
        return $service;
    }
    
    private function setupAdmin($email)
    {
        $this->info("\n4. Setting up admin user...");
        $admin = User::where('is_admin', true)->first();
        if ($admin) {
            $admin->email = $email;
            $admin->save();
            $this->info('✓ Using existing admin: ' . $admin->name . ' (ID: ' . $admin->id . ')');
        } else {
            $admin = User::first();
            if ($admin) {
                $admin->email = $email;
                $admin->is_admin = true;
                $admin->save();
                $this->info('✓ Updated user to admin: ' . $admin->name . ' (ID: ' . $admin->id . ')');
            } else {
                $admin = User::create([
                    'name' => 'Test Admin',
                    'email' => $email,
                    'password' => bcrypt('password'),
                    'is_admin' => true
                ]);
                $this->info('✓ Created new admin: ' . $admin->name . ' (ID: ' . $admin->id . ')');
            }
        }
        return $admin;
    }
    
    private function testCustomerEmails($customer, $service)
    {
        $this->info("\n5. Testing customer notification emails...");
        
        // Status update notification
        try {
            $customer->notify(new ServiceStatusUpdated($service));
            $this->info('✓ Status update notification sent');
        } catch (\Exception $e) {
            $this->error('✗ Status update notification failed: ' . $e->getMessage());
        }
        
        // Service reminder
        try {
            // Update service to be older for reminder test
            $service->status = 'in-progress';
            $service->start_date = Carbon::now()->subDays(4);
            $service->save();
            
            $customer->notify(new ServiceCompletionReminder($service));
            $this->info('✓ Service reminder sent');
        } catch (\Exception $e) {
            $this->error('✗ Service reminder failed: ' . $e->getMessage());
        }
        
        // Custom email
        try {
            Mail::to($customer->email)
                ->send(new CustomServiceEmail(
                    $service, 
                    'AutoX Custom Email Test', 
                    "This is a test of the custom email feature.\n\nYour vehicle service information is displayed below."
                ));
            $this->info('✓ Custom email sent');
        } catch (\Exception $e) {
            $this->error('✗ Custom email failed: ' . $e->getMessage());
        }
    }
    
    private function testAdminEmails($admin, $service)
    {
        $this->info("\n6. Testing admin notification emails...");
        
        // New service alert
        try {
            $admin->notify(new AdminServiceAlert($service, 'new_request'));
            $this->info('✓ New service alert sent');
        } catch (\Exception $e) {
            $this->error('✗ New service alert failed: ' . $e->getMessage());
        }
        
        // Overdue service alert
        try {
            // Update service to be even older for overdue test
            $service->start_date = Carbon::now()->subDays(6);
            $service->save();
            
            $admin->notify(new AdminServiceAlert($service, 'overdue'));
            $this->info('✓ Overdue service alert sent');
        } catch (\Exception $e) {
            $this->error('✗ Overdue service alert failed: ' . $e->getMessage());
        }
    }
}